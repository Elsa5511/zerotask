<?php

namespace Documentation\Factory\Service;

use Documentation\Factory\Service\HtmlContentServiceFactory;

class HtmlContentPageSectionServiceFactory extends HtmlContentServiceFactory
{

    protected function getRepositoryAsString()
    {

        return 'Documentation\Entity\HtmlContentPageSection';
    }

}