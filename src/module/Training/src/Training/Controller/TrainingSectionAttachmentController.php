<?php

namespace Training\Controller;

use Application\Controller\AttachmentWithLinkController;
use Training\Entity\TrainingSectionAttachment;

class TrainingSectionAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'training-section-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Training\Service\TrainingSectionAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($ownerId)
    {
        $section = $this->getOwnerEntityService()->getSection($ownerId);
        $sectionAttachment = new TrainingSectionAttachment();
        $sectionAttachment->setTrainingSection($section);
        return $sectionAttachment;
    }

    protected function getOwnerEntityService()
    {
        return $this->getServiceLocator()->get(
                        'Training\Service\TrainingSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Training\Entity\TrainingSectionAttachment';
    }

    protected function getOwnerController()
    {
        return 'training';
    }

    protected function getOwnerFieldName()
    {
        return 'training';
    }

}