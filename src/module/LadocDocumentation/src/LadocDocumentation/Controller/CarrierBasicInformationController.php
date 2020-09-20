<?php

namespace LadocDocumentation\Controller;


class CarrierBasicInformationController extends BasicInformationController {

    protected function createBasicInformationForm($formFactory) {
        return $formFactory->createCarrierBasicInformationForm();
    }

    protected function getBasicInformationService() {
        return $this->getRegisteredInstance('LadocDocumentation\Service\CarrierBasicInformationService');
    }

    protected function getControllerName() {
        return 'carrier-basic-information';
    }
}