<?php

namespace Training\Factory\Service;

use Application\Factory\Service\SectionServiceFactory;

class TrainingSectionServiceFactory extends SectionServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Training\Entity\TrainingSection';
    }
}

?>