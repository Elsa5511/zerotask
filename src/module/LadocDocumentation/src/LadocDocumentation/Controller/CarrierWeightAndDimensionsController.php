<?php

namespace LadocDocumentation\Controller;

use Zend\View\Model\ViewModel;

class CarrierWeightAndDimensionsController extends WeightAndDimensionsController {

    protected function createForm($formFactory) {
        return $formFactory->createCarrierForm();
    }

    protected function getWeightAndDimensionsService() {
        return $this->getRegisteredInstance('LadocDocumentation\Service\CarrierWeightAndDimensionsService');
    }

    protected function getPost() {
        $files = $this->request->getFiles()->toArray();
        $post = $this->request->getPost()->toArray();

        $service = $this->getWeightAndDimensionsService();
        return $service->mergeWithAttachments($post, $files);
    }

    protected function setupViewModel($form, $entity, $redirect, $error = null) {
        $viewModelData = array(
            'form' => $form,
            'type' => 'carrier',
            'showPreviousButton' => $redirect === $this->REDIRECT_WIZARD,
        );

        if ($entity !== null) {
            $viewModelData['imageFiles'] =$entity->getImageFiles();
            $viewModelData['documentationId'] =$entity->getLadocDocumentation()->getId();
        }

        $viewModel = new ViewModel($viewModelData);
        $viewModel->setTemplate('ladoc-documentation/weight-and-dimensions/add-edit');
        return $viewModel;
    }

    protected function getControllerName() {
        return 'carrier-weight-and-dimensions';
    }
}