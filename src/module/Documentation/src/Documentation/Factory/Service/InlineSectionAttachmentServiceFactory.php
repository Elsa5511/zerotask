<?php
namespace Documentation\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class InlineSectionAttachmentServiceFactory extends AttachmentServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\InlineSectionAttachment';
    }
}