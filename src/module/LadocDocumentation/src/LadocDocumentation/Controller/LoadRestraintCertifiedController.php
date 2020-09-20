<?php

namespace LadocDocumentation\Controller;

class LoadRestraintCertifiedController extends RestraintCertifiedController
{
    public function getType()
    {
        return 'load';
    }

    public function getControllerName()
    {
        return 'load-restraint-certified';
    }
}