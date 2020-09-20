<?php

namespace Documentation\Factory\Service;

use Application\Factory\Service\SectionServiceFactory;

class DocumentationSectionServiceFactory extends SectionServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\DocumentationSection';
    }
}

?>