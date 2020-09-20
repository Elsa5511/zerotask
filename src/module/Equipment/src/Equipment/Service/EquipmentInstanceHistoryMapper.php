<?php

namespace Equipment\Service;

class EquipmentInstanceHistoryMapper {
    
    public function map($equipmentInstance) {
        $equipmentInstanceHistorical = new \Equipment\Entity\EquipmentInstanceHistorical();
        
        $equipmentInstanceHistorical->setEquipmentInstance($equipmentInstance);
        $equipmentInstanceHistorical->setSerialNumber($equipmentInstance->getSerialNumber());
        $equipmentInstanceHistorical->setRegNumber($equipmentInstance->getRegNumber());
        $equipmentInstanceHistorical->setBatchNumber($equipmentInstance->getBatchNumber());
        $equipmentInstanceHistorical->setCertificateNumber($equipmentInstance->getCertificateNumber());
        $equipmentInstanceHistorical->setPurchaseDate($equipmentInstance->getPurchaseDate());
        $equipmentInstanceHistorical->setTechnicalLifetime($equipmentInstance->getTechnicalLifetime());
        $equipmentInstanceHistorical->setGuaranteeTime($equipmentInstance->getGuaranteeTime());
        $equipmentInstanceHistorical->setFirstTimeUsed($equipmentInstance->getFirstTimeUsed());
        $equipmentInstanceHistorical->setReceptionControl($equipmentInstance->getReceptionControl());
        $equipmentInstanceHistorical->setPeriodicControlDate($equipmentInstance->getPeriodicControlDate());
        $equipmentInstanceHistorical->setControlStatus($equipmentInstance->getControlStatus());
        $equipmentInstanceHistorical->setVisualControl($equipmentInstance->getVisualControl());
        $equipmentInstanceHistorical->setOrderNumber($equipmentInstance->getOrderNumber());
        $equipmentInstanceHistorical->setRfid($equipmentInstance->getRfid());
        $equipmentInstanceHistorical->setRemarks($equipmentInstance->getRemarks());
        $equipmentInstanceHistorical->setStatus($equipmentInstance->getStatus());
        $equipmentInstanceHistorical->setOwner($equipmentInstance->getOwner());
        $equipmentInstanceHistorical->setEquipment($equipmentInstance->getEquipment());
        $equipmentInstanceHistorical->setLocation($equipmentInstance->getLocation());
        $equipmentInstanceHistorical->setUsageStatus($equipmentInstance->getUsageStatus());
        $equipmentInstanceHistorical->setCheckedOut($equipmentInstance->isCheckedOut());
        $equipmentInstanceHistorical->setDateUpdated($equipmentInstance->getDateUpdated());
        $equipmentInstanceHistorical->setUpdatedBy($equipmentInstance->getUpdatedBy());
        
        return $equipmentInstanceHistorical;
    }
            
            
}
