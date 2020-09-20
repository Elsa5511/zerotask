<?php

namespace Equipment\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class EquipmentInstanceAttachmentServiceFactory extends AttachmentServiceFactory
{

    protected function getRepositoryAsString()
    {
        return 'Equipment\Entity\EquipmentInstanceAttachment';
    }

}