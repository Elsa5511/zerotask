<?php
namespace Equipment\Service;

use Application\Service\AbstractBaseService;

class VisualControlService extends AbstractBaseService
{

    protected function getEntityRepository()
    {
        return $this->getRepository('Equipment\Entity\VisualControl');
    }
    
    private function getEquipmentInstanceRepository()
    {
        return $this->getRepository('Equipment\Entity\EquipmentInstance');
    }
    
    private function getUserRepository()
    {
        return $this->getEntityManager()
                        ->getRepository('Application\Entity\User');
    }

    public function getLastVisualControl($equipmentInstanceId)
    {
        return $this->getEntityRepository()->findOneBy(
                    array('equipmentInstance' => $equipmentInstanceId), 
                    array('visualControlId'=>'DESC')
               );
    }
    
    public function getNewVisualControl() 
    {
        $visualControl = new \Equipment\Entity\VisualControl();
        return $visualControl;
    }

    public function checkEnabledVisualControl($instanceIds)
    {
        $flashMessengerArray = array();
        foreach ($instanceIds as $instanceId) {
            $equipmentInstance = $this->getEquipmentInstanceRepository()
                                                   ->find($instanceId);
            $isAbleToSave = (boolean) $equipmentInstance->getVisualControl();
            if(!$isAbleToSave) {
                $flashMessengerArray[] = sprintf($this->translate('"%s" has not visual control enabled'), 
                                                 $equipmentInstance->getSerialNumber());
                
            }
        }        
        return $flashMessengerArray;      
    }
    
    public function saveAll($visualControl, $instanceIds, $userId) {
        $flashMessengerArray = array();
        $currentUser = $this->getUserRepository()->find($userId);
        $visualControl->setRegisteredBy($currentUser);
        
        foreach ($instanceIds as $instanceId) {
            $entity = clone $visualControl;
            $flashMessengerArray[] = $this->saveOne($entity, $instanceId);
        }
        
        $this->getEntityManager()->flush();
        
        return $flashMessengerArray;
    }
    
    private function saveOne($entityToSave, $equipmentInstanceId) {
        $namespace = "error";
        $equipmentInstance = $this->getEquipmentInstanceRepository()
                                                   ->find($equipmentInstanceId);
        if ($equipmentInstance) {
            $isAbleToSave = (boolean) $equipmentInstance->getVisualControl();
            if($isAbleToSave) {
                $entityToSave->setEquipmentInstance($equipmentInstance);
                parent::persist($entityToSave);
                $this->updateEquipmentInstance($equipmentInstance);
                $namespace = "success";            
                $message = 
                        sprintf($this->translate('"%s" has been updated successfully'), 
                                $equipmentInstance->getSerialNumber());
            } else {
                $message = 
                        sprintf($this->translate('Visual control is not enabled for "%s".'), 
                                $equipmentInstance->getSerialNumber());
            }

        } else {            
            $message = $this->translate('Equipment Instance doesn\'t exist');
        }

        return array(
            'namespace' => $namespace,
            'message' => $message
         );
    }

    public function updateEquipmentInstance($equipmentInstance) {
        $controls = $equipmentInstance->getVisualControls();
        $lastControl = null;
        foreach ($controls as $control) {
            if ($lastControl === null
                || $lastControl->getControlDate() < $control->getControlDate()) {
                $lastControl = $control;
            }
        }
        if ($lastControl !== null) {
            $equipmentInstance->setMinVisualControlDate($lastControl->getNextControlDate());
            $visualControlStatus = $lastControl->getControlStatus() ? $lastControl->getControlStatus()->__toString() : null;
            $equipmentInstance->setVisualControlStatus($visualControlStatus);
            $this->persist($equipmentInstance);
        }
//        else {
//            $equipmentInstance->setPeriodicControlDate(new \DateTime());
//            $equipmentInstance->setControlStatus('Approved');
//        }
    }
}

