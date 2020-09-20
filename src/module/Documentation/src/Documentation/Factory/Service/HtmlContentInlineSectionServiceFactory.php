<?php

namespace Documentation\Factory\Service;

use Documentation\Factory\Service\HtmlContentServiceFactory;

class HtmlContentInlineSectionServiceFactory extends HtmlContentServiceFactory
{

    protected function getRepositoryAsString()
    {

        return 'Documentation\Entity\HtmlContentInlineSection';
    }

}