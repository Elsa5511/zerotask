<?php

namespace Training\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class TrainingSectionAttachmentServiceFactory extends AttachmentServiceFactory
{

    protected function getRepositoryAsString()
    {
        return 'Training\Entity\TrainingSectionAttachment';
    }

}

?>
