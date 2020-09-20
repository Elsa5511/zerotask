<?php

namespace LadocDocumentation\Controller;

class CarrierRestraintNonCertifiedController extends RestraintNonCertifiedController
{
    public function getType()
    {
        return 'carrier';
    }

    public function getControllerName()
    {
        return 'carrier-restraint-non-certified';
    }

}