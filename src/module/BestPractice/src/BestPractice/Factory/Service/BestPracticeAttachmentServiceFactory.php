<?php
namespace BestPractice\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class BestPracticeAttachmentServiceFactory extends AttachmentServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'BestPractice\Entity\BestPracticeAttachment';
    }
}