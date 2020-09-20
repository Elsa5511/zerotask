<?php

namespace Equipment\Service;

use Equipment\Service\EquipmentInstanceService;
use Sysco\Aurora\Stdlib\DateTime;
use \DateInterval;

class EquipmentInstanceControlDateService extends EquipmentInstanceService {

    public function updateDataByIds(array $ids) 
    {
    	$equipmentInstances = array();
    	foreach($ids as $id) {
    		$entity = $this->findById($id);
    		if($entity)	$equipmentInstances[] = $entity;
    	}

    	if(count($equipmentInstances) > 0) {
            $this->updateDataToEquipmentInstances($equipmentInstances);
            $this->updateParentsFromEquipmentInstances($equipmentInstances);
        }
    }

    public function checkMinDates() 
    {
    	if($this->getEntityRepository()->getNumberOfMinDatesFilled() == 0) {
    		$this->updateAllData();
    	}
    }

    private function updateAllData() 
    {
        $criteria = array('status' => 'active');
        $equipmentInstances = $this->getEntityRepository()->findBy($criteria);
        if(count($equipmentInstances) > 0)  $this->updateDataToEquipmentInstances($equipmentInstances);
    }

    /**
     * update equipment instances with:
     * earliest date in periodicControlDate, TechnicalLifetime and GuaranteeTime compared with it subinstances
     * the fields to update are minPeriodicControlDate, minTechnicalLifetime and minGuaranteeTime
     * @param array objects $equipmentInstances
     */
    private function updateDataToEquipmentInstances($equipmentInstances) 
    {
    	$entityManager = $this->getEntityManager();

        foreach ($equipmentInstances as $equipmentInstance) {
            $datesValues = $this->getLessDatesInSubInstances($equipmentInstance);

            $equipmentInstance->setMinPeriodicControlDate($datesValues['periodicControlDate']);
            $equipmentInstance->setMinTechnicalLifetime($datesValues['technicalLifetime']);
            $equipmentInstance->setMinGuaranteeTime($datesValues['guaranteeTime']);

            $entityManager->persist($equipmentInstance);
        }
        
        $entityManager->flush();
    }

    /*
     * this function update the parents' dates from all equipment instances passed by parameter
     * this function iterate over each instance, and update the dates of the directly parent until
     * the more distant parent. All of the parents's ids are saved in an array $parentIds, because in the next interation
     * is not necessary to update again a parent that has already updated in previous iterations
     * (the instances can share the same parent as a possibility)
     * @param array objects $equipmentInstances
     */
    private function updateParentsFromEquipmentInstances($equipmentInstances)
    {
        $entityManager = $this->getEntityManager();
        $parentIds = array();

        foreach ($equipmentInstances as $equipmentInstance) {
            $parentId = $equipmentInstance->getParentId();

            while($parentId > 0 && !in_array($parentId, $parentIds)) {
                $parent = $this->getEquipmentInstance($parentId);
                $datesValues = $this->getLessDatesInSubInstances($parent, false);

                $parent->setMinPeriodicControlDate($datesValues['periodicControlDate']);
                $parent->setMinTechnicalLifetime($datesValues['technicalLifetime']);
                $parent->setMinGuaranteeTime($datesValues['guaranteeTime']);

                $entityManager->persist($parent);

                $parentIds[] = $parentId;
                $parentId = $parent->getParentId();
            }
        }
        
        $entityManager->flush();
    }

	/**
     * Get the earliest dates in periodicControlDate, TechnicalLifetime and GuaranteeTime compared with it subinstances
     * You can specify if you want search recursively, or just in the directly subinstances
     * @param \Equipment\Entity\EquipmentInstance $parentEquipmentInstance
     * @param boolean $recursive
     * @return array
     */
    private function getLessDatesInSubInstances(\Equipment\Entity\EquipmentInstance $parentEquipmentInstance, $recursive = true)
    {
        $datesValues = array();

        $maximumDate = new DateTime('2038-01-19');
        $subinstances = $this->getSubinstancesByParentId($parentEquipmentInstance->getEquipmentInstanceId());

        $lessPeriodicControl = $parentEquipmentInstance->getPeriodicControlDate() ?: clone $maximumDate;
        $lessTechnicalLifetime = $parentEquipmentInstance->getTechnicalLifetime() ?: clone $maximumDate;
        $lessGuaranteeTime = $parentEquipmentInstance->getGuaranteeTime() ?: clone $maximumDate;

        foreach($subinstances as $equipmentInstance) {
            if($recursive)
                $datesValuesSubInstances = $this->getLessDatesInSubInstances($equipmentInstance);
            else
                $datesValuesSubInstances = array(
                        'periodicControlDate' => $equipmentInstance->getMinPeriodicControlDate(),
                        'technicalLifetime' => $equipmentInstance->getMinTechnicalLifetime(),
                        'guaranteeTime' => $equipmentInstance->getMinGuaranteeTime()
                    );

            $periodicControl = $datesValuesSubInstances['periodicControlDate'];
            if($periodicControl && $lessPeriodicControl > $periodicControl)
                $lessPeriodicControl = $periodicControl;

            $technicalLifetime = $datesValuesSubInstances['technicalLifetime'];
            if($technicalLifetime && $lessTechnicalLifetime > $technicalLifetime)
                $lessTechnicalLifetime = $technicalLifetime;

            $guaranteeTime = $datesValuesSubInstances['guaranteeTime'];
            if($guaranteeTime && $lessGuaranteeTime > $guaranteeTime)
                $lessGuaranteeTime = $guaranteeTime;
        }

        if($lessPeriodicControl != $maximumDate)    $datesValues['periodicControlDate'] = $lessPeriodicControl;
        else    $datesValues['periodicControlDate'] =  $parentEquipmentInstance->getPeriodicControlDate();

        if($lessTechnicalLifetime != $maximumDate)    $datesValues['technicalLifetime'] = $lessTechnicalLifetime;
        else    $datesValues['technicalLifetime'] =  $parentEquipmentInstance->getTechnicalLifetime();

        if($lessGuaranteeTime != $maximumDate)    $datesValues['guaranteeTime'] = $lessGuaranteeTime;
        else    $datesValues['guaranteeTime'] =  $parentEquipmentInstance->getGuaranteeTime();

        return $datesValues;
    }
}