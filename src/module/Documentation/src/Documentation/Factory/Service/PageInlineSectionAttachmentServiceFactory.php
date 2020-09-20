<?php
namespace Documentation\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class PageInlineSectionAttachmentServiceFactory extends AttachmentServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\PageInlineSectionAttachment';
    }
}