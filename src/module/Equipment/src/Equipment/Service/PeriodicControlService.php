<?php

namespace Equipment\Service;

use Application\Service\AbstractBaseService;
use Equipment\Entity\EquipmentInstance;
use Equipment\Entity\PeriodicControl;

class PeriodicControlService extends AbstractBaseService {

    protected function getEntityRepository() {
        return $this->getPeriodicControlRepository();
    }

    private function getPeriodicControlRepository() {
        return $this->getRepository('Equipment\Entity\PeriodicControl');
    }

    private function getEquipmentInstanceRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentInstance');
    }

    /**
     * @param $id
     * @return PeriodicControl
     */
    public function getPeriodicControl($id) {
        return $this->getPeriodicControlRepository()->find($id);
    }

    public function getLastPeriodicControl($equipmentInstanceId) {
        return $this->getPeriodicControlRepository()->findOneBy(
                        array('equipmentInstance' => $equipmentInstanceId), array('periodicControlId' => 'DESC')
        );
    }

    public function saveAll($periodicControl, $instanceIds, $attachmentService = null) {
        $flashMessengerArray = array();
        foreach ($instanceIds as $i => $instanceId) {
            $entity = clone $periodicControl;

            $attachments = $entity->getPeriodicControlAttachments();
            if($attachments && $attachments->count() > 0) {
                foreach($attachments as $periodicControlAttachment) {
                    $periodicControlAttachment->setPeriodicControl($entity);
                    if($i > 0 && $attachmentService)
                        $periodicControlAttachment->setFile($attachmentService->createDuplicateAttachmentFile($periodicControlAttachment->getFile()));
                }
            }

            $flashMessengerArray[] = $this->saveOne($entity, $instanceId);
        }

        $this->getEntityManager()->flush();

        return $flashMessengerArray;
    }

    private function saveOne($entityToSave, $equipmentInstanceId) {
        $equipmentInstance = $this->getEquipmentInstanceRepository()
                ->find($equipmentInstanceId);
        if ($equipmentInstance) {
            $entityToSave->setEquipmentInstance($equipmentInstance);
            parent::persist($entityToSave);

            $this->updateEquipmentInstance($equipmentInstance, $entityToSave);

            $namespace = "success";
            $message = sprintf($this->translate('"%s" has been updated successfully'), $equipmentInstance->getSerialNumber());
        } else {
            $namespace = "error";
            $message = $this->translate('Equipment Instance doesn\'t exist');
        }

        return array(
            'namespace' => $namespace,
            'message' => $message
        );
    }

    private function updateEquipmentInstance($equipmentInstance, $entityToSave) {
        $nextControlDate = $entityToSave->getNextControlDate();
        $controlStatus = $entityToSave->getControlStatus();
        $equipmentInstance->setPeriodicControlDate($nextControlDate);
        $equipmentInstance->setControlStatus($controlStatus);
        $this->getEntityManager()->persist($equipmentInstance);
    }

    public function updateEquipmentInstanceControlData(EquipmentInstance $equipmentInstance) {
        $periodicControls = $equipmentInstance->getPeriodicControls();
        $lastPeriodicControl = null;
        foreach ($periodicControls as $periodicControl) {
            if ($lastPeriodicControl === null
                || $lastPeriodicControl->getControlDate() < $periodicControl->getControlDate()) {
                $lastPeriodicControl = $periodicControl;
            }
        }
        if ($lastPeriodicControl !== null) {
            $equipmentInstance->setPeriodicControlDate($lastPeriodicControl->getNextControlDate());
            $equipmentInstance->setControlStatus($lastPeriodicControl->getControlStatus());
        }
        else {
            $equipmentInstance->setPeriodicControlDate(new \DateTime());
            $equipmentInstance->setControlStatus('Approved');
        }
        $this->persist($equipmentInstance);
    }
}
