<?php
namespace Documentation\Controller;

use Application\Controller\AttachmentWithLinkController;
use Documentation\Entity\DocumentationSectionAttachment;

class DocumentationSectionAttachmentController extends AttachmentWithLinkController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'documentation-section-attachment',   
        );
    }
    public function getAttachmentService()
    {
        return $this->getService('Documentation\Service\DocumentationSectionAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($sectionId)
    {
        $documentationSection = $this->getOwnerEntityService()->getSection($sectionId);
        $attachmentInstance = new DocumentationSectionAttachment();
        $attachmentInstance->setDocumentationSection($documentationSection);
        return $attachmentInstance;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\DocumentationSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\DocumentationSectionAttachment';
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