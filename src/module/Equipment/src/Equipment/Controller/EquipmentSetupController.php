<?php

namespace Equipment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\EventManagerInterface;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Equipment\Entity\ControlPointOption as ControlPointOption;
use Equipment\Entity\ControlTemplate as ControlTemplate;
use Equipment\Entity\ControlPoint as ControlPoint;
use Equipment\Entity\PeriodicControl as PeriodicControl;


/**
 * This controller is related to initalizing basic system setup and is to be run from the console 
 */
class EquipmentSetupController extends AbstractActionController {
    
    const ENTITY_NAMESPACE = 'Equipment\Entity';
    
    private $entityManager;
    
    private $controlPointOptions = array(
        'default' => array(
            array('label' => 'Remark'), 
            array('label' => 'Not controlled'), 
            array('label' => 'Not applicable'), 
            array('label' => 'OK')
        ),
        'job_type' => array(
            array('label' => 'Administrative'), 
            array('label' => 'Emergency repair'), 
            array('label' => 'Postregistration of equipment'),
            array('label' => 'Repair / maintenance error'),
            array('label' => 'Control error'), 
            array('label' => 'Reception error'), 
            array('label' => 'Warranty'),
            array('label' => 'Warranty inspection'), 
            array('label' => 'Inspection'), 
            array('label' => 'Installation'),
            array('label' => 'Calibration'), 
            array('label' => 'Configuration / equipment setup'), 
            array('label' => 'Contract'),
            array('label' => 'Upgrade / Update'), 
            array('label' => 'Training'), 
            array('label' => 'Cleaning'),
            array('label' => 'Recycling'),
            array('label' => 'Return after borrowing'),
            array('label' => 'Shell discarded'),
            array('label' => 'Accident / Near accident'),
            array('label' => 'Service'),
            array('label' => 'Lending'),
            array('label' => 'Equipment sale'),
            array('label' => 'Guard calling-out'),
            array('label' => 'Normal'),
            array('label' => 'Maintenance'),
        ),
        'error_code' => array(
            array('label' => 'User related'), 
            array('label' => 'Electric failure/wear'), 
            array('label' => 'Grounding failure'),
            array('label' => 'Error on consumables/accessories'),
            array('label' => 'Error due to deficient maintenance'),
            array('label' => 'Not coded'),
            array('label' => 'No error found'),
            array('label' => 'Leakage'),
            array('label' => 'Network error'),
            array('label' => 'Periodic error'),
            array('label' => 'Product weakness'),
            array('label' => 'Software'),
            array('label' => 'Damage from external strain'),
            array('label' => 'Loss of survey data'),
            array('label' => 'Out of adjustment'),
        ),
        'job_measures' => array(
            array('label' => 'Not performed'), 
            array('label' => 'None'), 
            array('label' => 'Substituted'),
            array('label' => 'Calibrated'),
            array('label' => 'Discarded'),
            array('label' => 'Modified'),
            array('label' => 'Upgraded/updated'),
            array('label' => 'Training'),
            array('label' => 'Repaired'),
            array('label' => 'Recycled'),
            array('label' => 'Returned'),
            array('label' => 'Put in storage'),
            array('label' => 'Sent to company'),
            array('label' => 'To be discarded'),
            array('label' => 'Performed by externals'),
            array('label' => 'Awaiting parts'),
            array('label' => 'Awaiting answer'),
            array('label' => 'Consider maintenance frequency'),
        ),
    );
        
    private $controlPoints = array(
        'default' => array(
            array('label' => 'Job type', 'collection_sets' => array('job_type')),
            array('label' => 'Error code', 'collection_sets' => array('error_code')),
            array('label' => 'Job measures', 'collection_sets' => array('job_measures')),
            array('label' => 'Labelling'),
            array('label' => 'Damages'),
            array('label' => 'Maintenance'),
            array('label' => 'Self-Test'),
            array('label' => 'Calibration'),
            array('label' => 'Function control'),
            array('label' => 'Documentation'),
            array('label' => 'El.safety test'),
            array('label' => 'Job number'),
            array('label' => 'Service report'),
        ),
        'x_ray' => array(
            array('label' => 'Kilovolt (KV)'),
            array('label' => 'Milliamperesecond (mAs)'),
            array('label' => 'Compliance brightfield and ray strengt'),
            array('label' => 'Image quality'),
            array('label' => 'Reception control'),
            array('label' => 'Constancy control'),
            array('label' => 'Status control'),
        ),
        'steril' => array(
            array('label' => 'Temperature measurement'),
            array('label' => 'Validation'),
        )
    );
        
    private $controlTemplates = array(
        array('name' => 'Default'),
        array('name' => 'Medical equipment'),
        array('name' => 'X-ray and radiation equipment', 'collection_sets' => array('default', 'x_ray')),
        array('name' => 'Steril production', 'collection_sets' => array('default', 'steril')),
        array('name' => 'Dental equipment'),
        array('name' => 'Laboratory equipment')
    );
 
    public function getEntityManager()
    {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }
    
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);
        $controller = $this;
        $controller->entityManager = $this->getEntityManager();
        $events->attach('dispatch', function ($e) use ($controller) {
                    if (!$controller->getRequest() instanceof ConsoleRequest) {
                        throw new \RuntimeException('You can only use this action from a console!');
                    }
                }, 100); // execute before executing action logic
        return $this;
    }

    public function setupEquipmentDataAction() {
        $this->setupControlTemplateData();
    }
    
    private function setupControlTemplateData() {
        $controlTemplates = $this->controlTemplates;
        $hydrator = new DoctrineHydrator($this->entityManager, 'Equipment\Entity\ControlTemplate');
        
        foreach($controlTemplates as $controlTemplate) {
            $controlTemplate = $this->addCollectionElements($controlTemplate, 'ControlPoint');
            $controlTemplate = $hydrator->hydrate($controlTemplate, new ControlTemplate());
            $this->entityManager->persist($controlTemplate); 
            $this->entityManager->flush();
        }
    }
    
    private function addCollectionElements($targetEntity, $entityName) {
        $entityClass = self::ENTITY_NAMESPACE.'\\'.$entityName;
        $collectionName = lcfirst($entityName).'Collection';
        $collectionSetsName = lcfirst($entityName).'s';
        $collectionSets = $this->$collectionSetsName;
            
        $usedCollectionSets = isset($targetEntity['collection_sets']) ? $targetEntity['collection_sets'] : array('default');
        $collectionElements = $this->getCollectionElements($collectionSets, $usedCollectionSets);
        
        foreach($collectionElements as $collectionElement) {
            $targetEntity[$collectionName][] = $this->addCollectionElement($collectionElement, $entityClass);
        }
        return $targetEntity;
    }
    
    private function getCollectionElements($collectionSets, $usedCollectionSets) {
        $collectionElements = array();
        foreach($usedCollectionSets as $collectionSet) {
            $collectionElements = array_merge($collectionElements, $collectionSets[$collectionSet]);
        }
        return $collectionElements;
    }
    
    private function addCollectionElement($collectionElement, $entityClass) {
        $existingElement = $this->entityManager->getRepository($entityClass)->findOneByLabel($collectionElement['label']);
        if($existingElement) {
            return $existingElement->getId();
        }
        else {
            return $this->createNewCollectionElement($collectionElement, $entityClass);
        }
    }
    
    private function createNewCollectionElement($collectionElement, $entityClass) {
        $hydrator = new DoctrineHydrator($this->entityManager, $entityClass);
        if($entityClass == 'Equipment\Entity\ControlPoint') {
            $collectionElement = $this->addCollectionElements($collectionElement, 'ControlPointOption');
        }
        $collectionElement = $hydrator->hydrate($collectionElement, new $entityClass());
        $this->entityManager->persist($collectionElement); 
        $this->entityManager->flush();
        return $collectionElement->getId();
    }
}

