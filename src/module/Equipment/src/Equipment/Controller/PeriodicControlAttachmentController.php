<?php

namespace Equipment\Controller;

use Application\Controller\AttachmentWithLinkController;
use Equipment\Entity\PeriodicControlAttachment;

class PeriodicControlAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'periodic-control-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Equipment\Service\PeriodicControlAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($ownerId)
    {
        $equipmentInstance = $this->getOwnerEntityService()->getPeriodicControl($ownerId);
        $attachmentInstance = new PeriodicControlAttachment();
        $attachmentInstance->setPeriodicControl($equipmentInstance);
        return $attachmentInstance;
    }

    protected function getOwnerEntityService()
    {
        return $this->getServiceLocator()->get(
                        'Equipment\Service\PeriodicControlService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Equipment\Entity\PeriodicControlAttachment';
    }

    protected function getOwnerController()
    {
        return 'equipment-instance';
    }

    protected function getOwnerFieldName()
    {
        return 'periodicControl';
    }

}