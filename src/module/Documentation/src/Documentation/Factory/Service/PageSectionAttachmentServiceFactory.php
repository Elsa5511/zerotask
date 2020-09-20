<?php
namespace Documentation\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class PageSectionAttachmentServiceFactory extends AttachmentServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\PageSectionAttachment';
    }
}