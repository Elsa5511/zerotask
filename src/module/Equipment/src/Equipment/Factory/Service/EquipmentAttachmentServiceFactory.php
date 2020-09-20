<?php

namespace Equipment\Factory\Service;

use Application\Factory\Service\AttachmentServiceFactory;

class EquipmentAttachmentServiceFactory extends AttachmentServiceFactory
{

    protected function getRepositoryAsString()
    {
        return 'Equipment\Entity\EquipmentAttachment';
    }

}