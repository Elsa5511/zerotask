<?php

namespace Equipment\Service;

use Application\Service\AbstractBaseService;
use Application\Service\LocationService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Query\QueryException;
use Equipment\Entity\ControlTemplate;
use Equipment\Entity\EquipmentInstance;
use Equipment\Service\Cache\EquipmentTaxonomyCache;
use Sysco\Aurora\Stdlib\DateTime;
use \DateInterval;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentInstanceService extends AbstractBaseService implements ServiceLocatorAwareInterface {

    private $serviceLocator;

    const ALIAS_KEY_RELATIONSHIPS = 'related';
    const MAXIMUM_RETURNED_INSTANCES = 1000;
    const MAXIMUM_RETURNED_CONTROLS = 1000;

    /**
     * @return \Equipment\Repository\EquipmentInstance
     */
    protected function getSuperRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentInstance');
    }

    /**
     * @return \Equipment\Repository\EquipmentInstance
     */
    protected function getEquipmentInstanceRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentInstance');
    }

    /**
     * @return \Equipment\Repository\PeriodicControl
     */
    protected function getPeriodicControlRepository() {
        return $this->getRepository('Equipment\Entity\PeriodicControl');
    }

    /**
     * @return \Equipment\Repository\VisualControl
     */
    protected function getVisualControlRepository() {
        return $this->getRepository('Equipment\Entity\VisualControl');
    }

    protected function getEquipmentInstanceContainerRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentInstanceContainer');
    }

    protected function getEntityRepository() {
        return $this->getEquipmentInstanceRepository();
    }

    private function getEquipmentTaxonomyRepository() {
        return $this->getEntityManager()->getRepository('Equipment\Entity\EquipmentTaxonomy');
    }

    /**
     * @return \Equipment\Service\Cache\EquipmentTaxonomyCacheService
     */
    private function getEquipmentTaxonomyCacheService() {
        return $this->getServiceLocator()->get('Equipment\Service\Cache\EquipmentTaxonomyCacheService');
    }

    /**
     * @return LocationService
     */
    private function getLocationService() {
        return $this->getServiceLocator()->get('Application\Service\LocationService');
    }

    private function getLocationRepository() {
        return $this->getRepository('Application\Entity\LocationTaxonomy');
    }

    private function getControlTemplateRepository() {
        return $this->getEntityManager()->getRepository('Equipment\Entity\ControlTemplate');
    }

    private function getUserRepository() {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

    public function getEquipmentInstancesSearch($params = array(), $includeInactive = true) {
        $locationIds = array();

        if (!empty($params['location'])) {
            $locationIds = $this->includeAllSubLocationIds($params['location']);
        }

        $equipmentInstanceRepository = $this->getEquipmentInstanceRepository();

        if($equipmentInstanceRepository->getEquipmentInstanceSearchCount($params, $locationIds, $includeInactive) > self::MAXIMUM_RETURNED_INSTANCES)
            throw new QueryException($this->translate("Too many instances were returned. Please add more filters"));

        $result = $equipmentInstanceRepository->getEquipmentInstancesSearch($params, $locationIds, $includeInactive);
        $this->setDataToEquipmentInstances($result);
        return $result;
    }

    private function includeAllSubLocationIds($locationId) {
        $locationService = $this->getLocationService();
        $location = $this->getLocationRepository()->find($locationId);
        $sublocations = $locationService->getSubLocations($location);
        array_push($sublocations, $location);
        return $locationService->extractIds($sublocations);
    }

    public function getInstancesControlSearch($params = array()) {
        $result = null;
        $repository = null;
        if ($params['controlType'] == 'periodic')
            $repository = $this->getPeriodicControlRepository();
        elseif ($params['controlType'] == 'visual')
            $repository = $this->getVisualControlRepository();
        else
            return null;

        if($repository->getControlsSearchCount($params) > self::MAXIMUM_RETURNED_CONTROLS)
            throw new QueryException($this->translate("Too many controls were returned. Please add more filters"));

        $result = $repository->getControlsSearch($params);
        return $result;
    }

    public function unlinkSubinstnace($equipmentToUnlinkId) {
        $equipmentInstance = $this->getEquipmentInstance($equipmentToUnlinkId);
        if (!empty($equipmentInstance)) {
            $equipmentInstance->setParentId(0);
            $this->persist($equipmentInstance);

            $message['message'] = $this->translate('Equipment instance unlinked');
            $message['namespace'] = 'success';
        }
        else {
            $message['message'] = $this->translate('Incorrect Equipment instance id');
            $message['namespace'] = 'error';
        }

        return $message;
    }

    public function getNewRegNumberByApplication($application) {
        if($application == "vedos-medical") {
            $like = 'A%';
            $regexp = '^A[0-9]{5}$';
            $initialValue = "A10000";
            $nextValueFormat = "A%s";
        } elseif($application == "vedos-mechanical") {
            $like = 'B%';
            $regexp = '^B[0-9]{5}$';
            $initialValue = "B10000";
            $nextValueFormat = "B%s";
        } else  return null;

        $lastRegNumber = $this->getEquipmentInstanceRepository()->getLastRegNumber($like, $regexp);
        if($lastRegNumber) {
            $numericPart = intval(substr($lastRegNumber, 1));
            $numericPart++;
            return sprintf($nextValueFormat, $numericPart);
        }

        return $initialValue;
    }

    /**
     * @param string $regNumber
     * @return bool
     */
    public function regNumberExists($regNumber, $excludeId = null, $equipmentId) {
        return !empty($regNumber)
        && $this->getEntityRepository()->regNumberExists($regNumber, $excludeId, $equipmentId);
    }

    /**
     * @param string $serialNumber
     * @return bool
     */
    public function serialNumberExists($serialNumber, $excluedId = null) {
        return $this->getEntityRepository()->serialNumberExists($serialNumber, $excluedId);
    }

    /**
     * @param array $expirationField - from Equipment\Entity\InstanceExpirationFieldTypes
     * @param string $idType (null|equipment|category)
     * @param (int|array) $id
     * @return array
     */
    public function getExpiredCounts(array $expirationFields, $idType = null, $id = null) {
        if ($id === null) {
            $idType = null;
        }

        if ($idType === 'category') {
            $id = $this->getParentAndChildrenCategories($id);
        }

        $expiredCounts = array();
        foreach($expirationFields as $expirationField)
            $expiredCounts[$expirationField] = $this->getSuperRepository()->getExpiredCount($expirationField, $idType, $id);

        return $expiredCounts;
    }

    /**
     * @param int $expirationField - from Equipment\Entity\InstanceExpirationFieldTypes
     * @param string $idType (null|"equipment"|"category")
     * @param (int|array) $id
     * @return int
     */
    public function getAllExpired($expirationField, $idType = null, $id = null) {
        if ($id === null) {
            $idType = null;
        }

        if ($idType === 'category') {
            $id = $this->getParentAndChildrenCategories($id);
        }

        return $this->getSuperRepository()->getAllExpired($expirationField, $idType, $id);
    }

    private function getParentAndChildrenCategories($id) {
        $parent = $this->getEquipmentTaxonomyRepository()->find($id);
        $categoryCacheService = $this->getEquipmentTaxonomyCacheService();
        $parentCache = new EquipmentTaxonomyCache();
        $parentCache->setEquipmentTaxonomyId($parent->getEquipmentTaxonomyId());
        $children = $categoryCacheService->getChildrenRecursive($parentCache);
        array_push($children, $parentCache);
        return $categoryCacheService->extractIds($children);
    }

    private function getEquipmentInstanceToArrayFiltered($equipmentInstanceToArray, $updateVisualControlOption) {
        if ($updateVisualControlOption == 1) {
            $removeAttributesArray = array('parentId' => 0, 'controlStatus' => '');
        }
        else {
            $removeAttributesArray = array('parentId' => 0, 'controlStatus' => '', 'visualControl' => '');
        }
        $equipmentInstanceToArrayPartialFiltered = array_diff_key(
            $equipmentInstanceToArray, $removeAttributesArray
        );

        $equipmentInstanceToArrayFiltered = array();
        foreach ($equipmentInstanceToArrayPartialFiltered as $key => $value) {
            if (!empty($value) || $key == 'visualControl') {
                $equipmentInstanceToArrayFiltered[$key] = $value;
            }
        }

        return $equipmentInstanceToArrayFiltered;
    }

    public function updateMany($equipmentInstanceToArray, $equipmentInstanceIds, $updateVisualControlOption) {

        $equipmentInstanceToArrayFiltered = $this->getEquipmentInstanceToArrayFiltered($equipmentInstanceToArray, $updateVisualControlOption);

        $equipmentInstanceList = $this->getEquipmentInstanceRepository()->fetchAllByIds($equipmentInstanceIds);

        foreach ($equipmentInstanceList as $equipmentInstance) {
            $this->setAttributesForEquipmentInstance($equipmentInstance, $equipmentInstanceToArrayFiltered);
        }
        $this->getEntityManager()->flush();
    }

    private function setAttributesForEquipmentInstance($equipmentInstance, $equipmentInstanceToArrayFiltered) {
        foreach ($equipmentInstanceToArrayFiltered as $attribute => $value) {

            $method = "set" . ucwords($attribute);
            $equipmentInstance->$method($value);
        }
    }

    /**
     * Finds an equipment instance by its primary key / identifier.
     * @param int $equipmentInstanceId
     * @return EquipmentInstance
     */
    public function getEquipmentInstance($equipmentInstanceId) {
        return $this->getEquipmentInstanceRepository()->find($equipmentInstanceId);
    }

    /**
     * Finds entities in the repository.
     * @param array $criteria
     * @param string $orderBy
     * @param int $limit
     * @param int $offset
     * @return array The objects.
     */
    public function getEquipmentInstances($criteria, $orderBy = null, $limit = null, $offset = null) {
        return $this->getSuperRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Setting additional values and
     * persisting the entity to database.
     *
     */
    public function saveEquipmentInstance($equipmentInstance, $userId = 0) {
        $calculateControlDate = !($equipmentInstance->getPeriodicControlDate());
        if ($calculateControlDate) {
            $equipmentInstance = $this->calculateAndSetNextControlDate($equipmentInstance);
        }
        $nowTime = new \DateTime('now');
        $equipmentInstance->setDateUpdated($nowTime);

        $user = $this->getUserRepository()->find($userId);
        $equipmentInstance->setUpdatedBy($user);

        $equipmentInstanceHistoricalCopy = $this->createHistoricalCopyFrom($equipmentInstance);
        $entityManager = $this->getEntityManager();

        $equipmentInstance->setApplication($this->application);
        if($equipmentInstance->getPrice() == '')
            $equipmentInstance->setPrice(null);
        $entityManager->persist($equipmentInstance);
        $entityManager->persist($equipmentInstanceHistoricalCopy);
        $entityManager->flush();
        return $equipmentInstance->getEquipmentInstanceId();
    }

    private function createHistoricalCopyFrom($equipmentInstance) {
        $mapperToHistoricalCopy = new EquipmentInstanceHistoryMapper();
        $equipmentInstanceHistoricalCopy = $mapperToHistoricalCopy->map($equipmentInstance);
        return $equipmentInstanceHistoricalCopy;
    }

    public function updateEquipmentSubinstance($subinstanceId, $parentId) {
        $equipmentInstance = $this->getSuperRepository()
            ->find($subinstanceId);
        $equipmentInstance->setParentId($parentId);
        $this->persist($equipmentInstance);
    }

    /**
     * @param \Equipment\Entity\EquipmentInstance $equipmentInstance
     * @param \Application\Entity\Organization|null $organization
     * @return array
     */
    public function getAvailableEquipmentInstance($equipmentInstance, $organization = null) {
        $equipmentInstanceId = $equipmentInstance->getEquipmentInstanceId();
        $potencialChildren = $this->getSuperRepository()
            ->fetchPotentialChildren($equipmentInstanceId);
        $availableEquipmentInstances = array();
        $equipmentInstanceParents = $this->getParents($equipmentInstance);
        foreach ($potencialChildren as $potencialChild) {
            $potencialChildId = $potencialChild->getEquipmentInstanceId();
            $isSuperiorLevel = $this->isSuperiorLevel($potencialChildId, $equipmentInstanceParents);

            if (!$isSuperiorLevel) {
                //Used to filter by organization
                if($organization != null) {
                    if ($potencialChild->getOwner() == null )   continue;
                    if($organization->getOrganizationId() != $potencialChild->getOwner()->getOrganizationId())
                        continue;
                }

                $availableEquipmentInstances[$potencialChildId] = $potencialChild->getSerialNumber() .
                    ' (' . $potencialChild->getEquipment()->getTitle() . ')';
            }
        }
        return $availableEquipmentInstances;
    }

    private function isSuperiorLevel($potencialChildId, $parentIds) {
        return in_array($potencialChildId, $parentIds);
    }

    /**
     * This function returns an array of parents starting with the superparent as a first element
     * until the directly parent related to the equipment instance passed as a parameter
     * @param EquipmentInstance $equipmentInstance
     * @return array
     */
    private function getParents($equipmentInstance) {

        if ($equipmentInstance->getParentId() != 0) {
            $parent = $this->getParent($equipmentInstance->getParentId());
            $parents = $this->getParents($parent);
            array_push($parents, $parent->getEquipmentInstanceId());
            return $parents;
        }
        else {
            return array();
        }
    }

    private function getParent($equipmentInstanceId) {
        $equipmentInstance = $this->getEquipmentInstance($equipmentInstanceId);
        return $equipmentInstance;
    }

    /**
     *
     * @param int $equipmentInstanceId
     * @return array objects
     */
    public function getSubinstancesByParentId($equipmentInstanceId) {
        $equipmentInstances = $this->getEquipmentInstances(array('parentId' => $equipmentInstanceId));
        $equipmentInstancesValidated = $this->setDataToEquipmentInstances($equipmentInstances);

        return $equipmentInstancesValidated;
    }

    /**
     *
     * @param entity $equipment
     * @param entity $organization
     * @return array objects
     */
    public function getEquipmentInstanceBelongEquipment($equipment, $organization) {
        $criteria = array('equipment' => $equipment);
        if($organization != null)
            $criteria['owner'] = $organization;
        $equipmentInstances = $this->getEquipmentInstances($criteria);
        $equipmentInstancesValidated = $this->setDataToEquipmentInstances($equipmentInstances);

        return $equipmentInstancesValidated;
    }

    /**
     * return equipment instances with:
     * control status expired if today is more that control status date (subinstances are checked as well)
     * earliest date in periodicControlDate, TechnicalLifetime and GuaranteeTime compared with it subinstances
     * @param array objects $equipmentInstances
     * @return array objects
     */
    private function setDataToEquipmentInstances($equipmentInstances) {
        foreach ($equipmentInstances as $equipmentInstance) {
            $expired = $equipmentInstance->isDateExpired('minPeriodicControlDate');
            if ($expired) $equipmentInstance->setControlStatus('expired');
        }
        return $equipmentInstances;
    }

    /**
     *
     * @param EquipmentInstance $equipmentInstance
     * @return EquipmentInstance
     */
    public function calculateAndSetNextControlDate(\Equipment\Entity\EquipmentInstance $equipmentInstance) {
        $purchaseDate = $equipmentInstance->getPurchaseDate();
        $receptionControlDate = $equipmentInstance->getReceptionControl();
        $firstTimeUsed = $equipmentInstance->getFirstTimeUsed();
        $datesArray = array($purchaseDate, $receptionControlDate, $firstTimeUsed);
        $baseControlDate = max($datesArray);

        if (!empty($baseControlDate)) {
            $controlIntervalByDays = $equipmentInstance->getEquipment()->getControlIntervalByDays();
            if ($controlIntervalByDays) {
                $stringToTime = 'P' . $controlIntervalByDays . 'D';
                $anyDate = clone $baseControlDate;
                $periodicControlDate = $anyDate->add(new DateInterval($stringToTime));
            }
        }
        else {
            $periodicControlDate = new DateTime();
        }
        $equipmentInstance->setPeriodicControlDate($periodicControlDate);
        return $equipmentInstance;
    }

    public function checkEquipmentInstanceExists($equipmentInstanceId) {
        $equipmentInstanceExists = false;
        if ($equipmentInstanceId) {
            $equipmentInstance = $this->getEquipmentInstance($equipmentInstanceId);

            if (!empty($equipmentInstance)) {
                return $equipmentInstance;
            }
        }
        return $equipmentInstanceExists;
    }

    /**
     * @param EquipmentInstance $controlInstance
     * @return ControlTemplate
     */
    public function getControlTemplate(\Equipment\Entity\EquipmentInstance $controlInstance) {
        $templateCategory = $controlInstance->getEquipment()->getFirstEquipmentTaxonomy();
        $controlTemplate = $templateCategory->getControlTemplate();

        while ($controlTemplate == null && $templateCategory = $this->getParentCategory($templateCategory)) {
            $controlTemplate = $templateCategory->getControlTemplate();
        }

        if ($controlTemplate == null) {
            return $this->getDefaultControlTemplate();
        }
        else {
            return $controlTemplate;
        }
    }

    private function getParentCategory(\Equipment\Entity\EquipmentTaxonomy $category) {
        return $this->getEquipmentTaxonomyRepository()->find($category->getParentId());
    }

    private function getDefaultControlTemplate() {
        $defaultControlTemplate = $this->getControlTemplateRepository()->findOneBy(array('name' => 'Default')); //findAll(); //findOneByName('Default');
        return $defaultControlTemplate;
    }

    public function getControlPointToTemplateArray($equipmentInstanceIds) {
        $firstControlInstance = $this->getEquipmentInstance($equipmentInstanceIds[0]);
        if ($firstControlInstance) {
            $controlTemplate = $this->getControlTemplate($firstControlInstance);
            return $controlTemplate->getOrderedControlPointsToTemplate();
        }
        else {
            return new ArrayCollection();
        }
    }

    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    /**
     * @param Collection $instances
     */
    public function extractIds($instances) {
        $ids = array();

        foreach ($instances as $instance) {
            array_push($ids, $instance->getEquipmentInstanceId());
        }
        return $ids;
    }

}
