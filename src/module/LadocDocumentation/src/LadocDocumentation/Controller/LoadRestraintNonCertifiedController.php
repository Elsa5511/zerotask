<?php

namespace LadocDocumentation\Controller;

class LoadRestraintNonCertifiedController extends RestraintNonCertifiedController
{
    public function getType()
    {
        return 'load';
    }

    public function getControllerName()
    {
        return 'load-restraint-non-certified';
    }

}