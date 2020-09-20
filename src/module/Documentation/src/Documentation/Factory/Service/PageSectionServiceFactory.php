<?php

namespace Documentation\Factory\Service;

use Application\Factory\Service\SectionServiceFactory;

class PageSectionServiceFactory extends SectionServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\PageSection';
    }
}

?>