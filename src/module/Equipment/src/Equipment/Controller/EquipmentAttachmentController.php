<?php

namespace Equipment\Controller;

use Application\Controller\AttachmentController;
use Application\Controller\AttachmentWithLinkController;
use Equipment\Entity\EquipmentAttachment;

class EquipmentAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'equipment-attachment',
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Equipment\Service\EquipmentAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($ownerId)
    {
        $equipment = $this->getOwnerEntityService()->getEquipment($ownerId);
        $attachment = new EquipmentAttachment();
        $attachment->setEquipment($equipment);
        return $attachment;
    }

    protected function getOwnerEntityService()
    {
        return $this->getServiceLocator()->get(
                        'Equipment\Service\EquipmentService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Equipment\Entity\EquipmentAttachment';
    }

    protected function getOwnerController()
    {
        return 'equipment';
    }

    protected function getOwnerFieldName()
    {
        return 'equipment';
    }

}