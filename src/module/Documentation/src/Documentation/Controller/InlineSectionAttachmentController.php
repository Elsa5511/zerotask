<?php
namespace Documentation\Controller;

use Application\Controller\AttachmentWithLinkController;
use Documentation\Entity\InlineSectionAttachment;

class InlineSectionAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'inline-section-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Documentation\Service\InlineSectionAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($sectionId)
    {
        $inlineSection = $this->getOwnerEntityService()->getSection($sectionId);
        $attachmentInstance = new InlineSectionAttachment();
        $attachmentInstance->setInlineSection($inlineSection);
        return $attachmentInstance;
    }

    protected function getOwnerEntityService()
    {
        return $this->getServiceLocator()->get(
                        'Documentation\Service\InlineSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\InlineSectionAttachment';
    }

    protected function getOwnerController()
    {
        return 'documentation';
    }

    protected function getOwnerFieldName()
    {
        return 'documentation';
    }

}