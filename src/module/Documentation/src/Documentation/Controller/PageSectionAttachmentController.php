<?php
namespace Documentation\Controller;

use Application\Controller\AttachmentWithLinkController;
use Documentation\Entity\PageSectionAttachment;

class PageSectionAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'attachment-section-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Documentation\Service\PageSectionAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($sectionId)
    {
        $pageSection = $this->getOwnerEntityService()->getSection($sectionId);
        $attachment = new PageSectionAttachment();
        $attachment->setPageSection($pageSection);
        return $attachment;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\PageSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\PageSectionAttachment';
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