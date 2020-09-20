<?php

namespace Documentation\Factory\Service;

use Documentation\Factory\Service\HtmlContentServiceFactory;

class HtmlContentPageInlineSectionServiceFactory extends HtmlContentServiceFactory
{

    protected function getRepositoryAsString()
    {

        return 'Documentation\Entity\HtmlContentPageInlineSection';
    }

}