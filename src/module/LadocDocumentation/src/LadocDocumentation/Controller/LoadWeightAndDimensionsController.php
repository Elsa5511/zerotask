<?php

namespace LadocDocumentation\Controller;

use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Service\LoadWeightAndDimensionsService;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;

class LoadWeightAndDimensionsController extends WeightAndDimensionsController {

    protected function createForm($formFactory) {
        return $formFactory->createLoadForm($this->getMode());
    }

    /**
     * @return LoadWeightAndDimensionsService
     */
    protected function getWeightAndDimensionsService() {
        return $this->getRegisteredInstance('LadocDocumentation\Service\LoadWeightAndDimensionsService');
    }

    protected function getPost() {
        $files = $this->request->getFiles()->toArray();
        $post = $this->request->getPost()->toArray();

        $service = $this->getWeightAndDimensionsService();
        $a = $service->mergeWithAttachments($post, $files);

        $postObj = new Parameters();
        $postObj->fromArray($a);
        return $postObj;
    }

    protected function setupViewModel($form, $entity, $redirect, $error = null) {
        $viewModel = new ViewModel(array(
            'form' => $form,
            'type' => 'load',
            'documentationId' => $entity->getLadocDocumentation()->getId(),
            'showPreviousButton' => $redirect === $this->REDIRECT_WIZARD,
            'customError' => $error,
            'attachments' => $entity->getAttachments(),
        ));
        $viewModel->setTemplate('ladoc-documentation/weight-and-dimensions/add-edit');
        return $viewModel;
    }

    protected function getControllerName() {
        return 'load-weight-and-dimensions';
    }

    protected function addEditPostAction($form, $redirect)
    {
        $error = array();
        $service = $this->getWeightAndDimensionsService();
        $post = $this->getPost();
        $form->setData($post);
        $isFormValid = $form->isValid();

        $weightAndDimensions = $form->getObject();
        $imagesAreValid = true;
        if($this->getMode() == 'edit') {
            $imagesAreValid = $service->validateForCustom($post, $error);

            $messages = $form->getInputFilter()->getMessages();
            if(!isset($post['weight-and-dimensions']['attachments']) &&
                isset($messages['weight-and-dimensions']) && count($messages['weight-and-dimensions']) == 1 &&
                isset($messages['weight-and-dimensions']['attachments'])) {
                $isFormValid = true;
                $weightAndDimensions->removeAttachments();
            }
        }


        if ($isFormValid && $imagesAreValid) {

            $service->savePostedData($weightAndDimensions, $post);
            if ($redirect === $this->REDIRECT_WIZARD) {
                $direction = $post['weight-and-dimensions']['direction'];
                return $this->redirectToPath(
                    'ladoc-documentation', 'wizard', array(
                    'id' => $weightAndDimensions->getLadocDocumentation()->getId(),
                    'current_page' => LadocDocumentation::PAGE_WEIGHT_AND_DIMENSIONS,
                    'direction' => $direction
                ));
            }
            else if ($redirect === $this->REDIRECT_DISPLAY) {
                return $this->redirectToPath(
                    'ladoc-documentation', 'display', array(
                    'id' => $weightAndDimensions->getLadocDocumentation()->getId()
                ));
            }
        }
        else {
//            var_dump($imagesAreValid);
//            var_dump($post);
//            die("YO");

            $id = $this->params()->fromRoute('id', 0);
            $service = $this->getWeightAndDimensionsService();
            $weightAndDimensions = $service->findById($id);
            $this->sendTranslatedFlashMessage($form->getMessages(), 'failure');
//            $documentationId = $this->params()->fromRoute('documentation_id', 0);
            return $this->setupViewModel($form, $weightAndDimensions, $redirect, $error);
            //redirectToReferer();
        }
    }
}