<?php

namespace LadocDocumentation\Controller;

class CarrierRestraintCertifiedController extends RestraintCertifiedController
{
    public function getType()
    {
        return 'carrier';
    }

    public function getControllerName()
    {
        return 'carrier-restraint-certified';
    }

}