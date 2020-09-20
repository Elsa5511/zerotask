<?php

namespace Documentation\Factory\Service;

use Application\Factory\Service\SectionServiceFactory;

class PageInlineSectionServiceFactory extends SectionServiceFactory
{
    protected function getRepositoryAsString()
    {
        return 'Documentation\Entity\PageInlineSection';
    }
}