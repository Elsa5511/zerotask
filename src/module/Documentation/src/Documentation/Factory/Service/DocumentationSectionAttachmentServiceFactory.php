<?php
namespace Documentation\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class DocumentationSectionAttachmentServiceFactory extends AttachmentServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\DocumentationSectionAttachment';
    }
}