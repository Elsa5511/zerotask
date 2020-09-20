<?php
namespace Documentation\Controller;

use Application\Controller\AttachmentWithLinkController;
use Documentation\Entity\PageInlineSectionAttachment;

class PageInlineSectionAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'page-inline-section-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Documentation\Service\PageInlineSectionAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($sectionId)
    {
        $inlineSection = $this->getOwnerEntityService()->getSection($sectionId);
        $attachment = new PageInlineSectionAttachment();
        $attachment->setPageInlineSection($inlineSection);
        return $attachment;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\PageInlineSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\PageInlineSectionAttachment';
    }

    protected function getOwnerController()
    {
        return 'page';
    }

    protected function getOwnerFieldName()
    {
        return 'page';
    }

}