<?php
namespace Application\Service;

use Acl\Service\AbstractService;
use Application\Entity\LocationTaxonomy;
use Application\Repository\LocationRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationService extends AbstractService implements ServiceLocatorAwareInterface {
    
    const ALIAS_KEY_RELATIONSHIPS = 'related';

    private $serviceLocator;

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
     * @return LocationRepository
     */
    public function getEntityRepository() {
        return $this->getRepository('Application\Entity\LocationTaxonomy');
    }

    public function getLocation($locationId) {
        return $this->getEntityRepository()->find($locationId);
    }

    /**
     * @return \Application\Service\Cache\LocationCacheService
     */
    private function getLocationCacheService() {
        return $this->getServiceLocator()->get('Application\Service\Cache\LocationCacheService');
    }

    /**
     * @param LocationTaxonomy $parent
     * @return array
     */
    public function getSubLocations(LocationTaxonomy $parent) {
        $repository = $this->getEntityRepository();
        $children = $repository->findFirstLevelChildren($parent->getLocationTaxonomyId());

        foreach ($children as $child) {
            $children = array_merge($children, $this->getSubLocations($child));
        }

        return $children;
    }

    public function extractIds($locationArray) {
        $ids = array();
        foreach ($locationArray as $location) {
            array_push($ids, $location->getLocationTaxonomyId());
        }
        return $ids;
    }


    /**
     * Return a list of all locations
     */
    public function fetchAll() {
        return $this->getEntityRepository()->findAll();
    }

    public function persistData($location) {        
        parent::persist($location);
        $this->getLocationCacheService()->loadCacheData(); //update cache for locations
        return $location->getLocationTaxonomyId();
    }
    
    public function isLocationUnique($location)
    {
        $slug = $this->getSlugAsFullname($location);
        $validator = new \DoctrineModule\Validator\UniqueObject(array(
            'object_manager' => $this->getEntityManager(),
            'object_repository' => $this->getEntityRepository(),
            'fields' => array('slug'),
            'use_context' => true
        ));
        $locationTaxonomyId = $location->getLocationTaxonomyId() ?: 'identifier';
        $isUnique = $validator->isValid(array($slug), array('locationTaxonomyId' => $locationTaxonomyId));
        if($isUnique) {
            $location->setSlug($slug);            
        }
        return $isUnique;
    }
    
    private function getSlugAsFullname($location) {
        $slug = $location->getName() . "/";
        $parent = $location->getParent();
        if ($parent) {
            $parentSlug = $parent->getSlug();
            $slug = $parentSlug . $slug;
        }
        return $slug;
    }

    public function deleteById($locationId, $updateCache = true) {
        $location = $this->getEntityRepository()->find($locationId);
        if ($location) {
            $entitiesRelated = $this->getEntityRepository()->getEntitiesRelated(
                $locationId);
            $hasRelationships = sizeof($entitiesRelated) > 0;
            $locationName = $location->getName();

            if ($hasRelationships) {
                throw new \Application\Service\CannotDeleteException(
                        $this->getRelationshipsErrorMessage($locationName, $entitiesRelated));
                
            } else {
                $this->remove($location);
                $message = $this->translate('Location has been deleted successfully');
                $serviceMessage = new \Application\Utility\ServiceMessage('success', $message);

                if($updateCache)    $this->getLocationCacheService()->loadCacheData(); //update cache for locations
            }
        } else {
            $message = sprintf($this->translate("Could not find location with id %u"), $locationId);
            throw new EntityDoesNotExistException($message);
        }

        return $serviceMessage;
     }
     
     public function deleteByIds($locationIds) {
        $serviceMessageArray = array();
        foreach ($locationIds as $locationId) {
            try {
                array_push($serviceMessageArray, $this->deleteById($locationId, false));
            } catch (ServiceOperationException $exception) {
                $serviceMessage = new \Application\Utility\ServiceMessage('error',
                        $exception->getMessage());
                array_push($serviceMessageArray, $serviceMessage);
            }
        }

         $this->getLocationCacheService()->loadCacheData(); //update cache for locations
        return $serviceMessageArray;
     }

     private function getRelationshipsErrorMessage($locationName, $entitiesRelated) {
        $separator = " - ";
        $messageError = '"' . $locationName . '" ' .
             $this->translate(
                'can\'t be deleted because it is related to other entities.') . " ".
             $this->translate('Edit the relationships and try again:') . " ";
        foreach ($entitiesRelated as $entityMessage) {
            $messageError .= $separator .
                 $entityMessage[self::ALIAS_KEY_RELATIONSHIPS];
        }
        return $messageError;
     }

}
