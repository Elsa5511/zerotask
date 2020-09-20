<?php

namespace Documentation\Factory\Service;

use Documentation\Factory\Service\HtmlContentServiceFactory;

class HtmlContentDocumentationSectionServiceFactory extends HtmlContentServiceFactory
{

    protected function getRepositoryAsString()
    {

        return 'Documentation\Entity\HtmlContentDocumentationSection';
    }

}