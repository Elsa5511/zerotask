<?php

namespace Equipment\Controller;

use Application\Controller\AttachmentWithLinkController;
use Equipment\Entity\EquipmentInstanceAttachment;

class EquipmentInstanceAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'equipment-instance-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Equipment\Service\EquipmentInstanceAttachmentService');
    }

    public function getEquipmentAttachmentService()
    {
        return $this->getService('Equipment\Service\EquipmentAttachmentService');
    }

    public function getEquipmentInstanceService()
    {
        return $this->getService('Equipment\Service\EquipmentInstanceService');
    }

    protected function getAttachmentEntityWithOwner($ownerId)
    {
        $equipmentInstance = $this->getOwnerEntityService()->getEquipmentInstance($ownerId);
        $attachmentInstance = new EquipmentInstanceAttachment();
        $attachmentInstance->setEquipmentInstance($equipmentInstance);
        return $attachmentInstance;
    }

    public function getAdditionalAttachments()
    {
        $ownerId = $this->params()->fromRoute('id', 0);
        $equipmentIInstance= $this->getEquipmentInstanceService()->findById($ownerId);
        $attachments = $this->getEquipmentAttachmentService()->fetchAttachment(array('equipment' => $equipmentIInstance->getEquipment()->getEquipmentId()));

        return $attachments;
    }

    protected function getViewPath()
    {
        return 'equipment/equipment-instance/attachment-table.phtml';
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Equipment\Service\EquipmentInstanceService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Equipment\Entity\EquipmentInstanceAttachment';
    }

    protected function getOwnerController()
    {
        return 'equipment-instance';
    }

    protected function getOwnerFieldName()
    {
        return 'equipmentInstance';
    }

}