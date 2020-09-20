<?php

namespace LadocDocumentation\Controller;


class LoadBasicInformationController extends BasicInformationController {

    protected function createBasicInformationForm($formFactory) {
        return $formFactory->createLoadBasicInformationForm();
    }

    protected function getBasicInformationService() {
        return $this->getRegisteredInstance('LadocDocumentation\Service\LoadBasicInformationService');
    }

    protected function getControllerName() {
        return "load-basic-information";
    }
}