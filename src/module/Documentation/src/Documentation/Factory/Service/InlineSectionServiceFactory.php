<?php

namespace Documentation\Factory\Service;

use Application\Factory\Service\SectionServiceFactory;

class InlineSectionServiceFactory extends SectionServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\InlineSection';
    }
}