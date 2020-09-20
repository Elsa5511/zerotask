<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use Application\Service\ServiceOperationException;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Entity\WeightAndDimensions;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Service\WeightAndDimensionsService;

abstract class WeightAndDimensionsController extends AbstractBaseController {

    protected $mode = 'add';

    protected abstract function createForm($formFactory);

    /**
     * @return WeightAndDimensionsService
     */
    protected abstract function getWeightAndDimensionsService();
    protected abstract function getPost();
    protected abstract function setupViewModel($form, $documentationId, $redirect, $error = null);
    protected abstract function getControllerName();
//    protected abstract function validateForCustom($postData, &$error);

    protected $REDIRECT_WIZARD = 1;
    protected $REDIRECT_DISPLAY = 2;

    public function addAction() {
        $documentationId = $this->params()->fromRoute('documentation_id', 0);

        $existingWeightAndDimensions = $this->getExistingWeightAndDimensions($documentationId);
        if ($existingWeightAndDimensions) {
            return $this->redirectToPath($this->getControllerName(), 'edit-wizard', array(
                'id' => $existingWeightAndDimensions->getId(),
            ));
        }

        $weightAndDimensions = $this->tryToGetNewWeightAndDimensions();
        if (!$weightAndDimensions) {
            return $this->redirectToReferer();
        }

        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $weightAndDimensions->getLadocDocumentation());
        $form = $this->createAndBindForm($weightAndDimensions);

        if ($this->getRequest()->isPost()) {
            return $this->addEditPostAction($form, $this->REDIRECT_WIZARD);
        }
        else {
            return $this->setupViewModel($form, $weightAndDimensions, $this->REDIRECT_WIZARD);
        }
    }

    private function getExistingWeightAndDimensions($documentationId) {
        $service = $this->getWeightAndDimensionsService();

        $weightAndDimensions = $service->findByDocumentationId($documentationId);

        return ($weightAndDimensions);
    }

    public function editAction() {
        return $this->editActionInternal($this->REDIRECT_DISPLAY);
    }

    public function editWizardAction() {
        return $this->editActionInternal($this->REDIRECT_WIZARD);
    }

    private function editActionInternal($redirect) {
        $this->mode = 'edit';
        $id = $this->params()->fromRoute('id', 0);
        $service = $this->getWeightAndDimensionsService();
        $weightAndDimensions = $service->findById($id);
        if ($weightAndDimensions) {
            BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $weightAndDimensions->getLadocDocumentation());
            $weightAndDimensions->loadReferences();
            $form = $this->createAndBindForm($weightAndDimensions);
            if ($this->getRequest()->isPost()) {
                return $this->addEditPostAction($form, $redirect);
            }
            else {
                return $this->setupViewModel($form, $weightAndDimensions, $redirect);
            }
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function addEditPostAction($form, $redirect)
    {
        $service = $this->getWeightAndDimensionsService();
        $post = $this->getPost();
        $form->setData($post);

        if ($form->isValid()) {
            $weightAndDimensions = $form->getObject();
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
            $id = $this->params()->fromRoute('id', 0);
            $service = $this->getWeightAndDimensionsService();
            $weightAndDimensions = $service->findById($id);
            $this->sendTranslatedFlashMessage($form->getMessages(), 'failure');
            return $this->setupViewModel($form, $weightAndDimensions, $redirect);
        }
    }

    /**
     * @return WeightAndDimensions
     */
    private function tryToGetNewWeightAndDimensions() {
        try {
            $documentationId = $this->params()->fromRoute('documentation_id', 0);
            $service = $this->getWeightAndDimensionsService();
            return $service->createNewWeightAndDimensions($documentationId);
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    private function createAndBindForm($weightAndDimenstions) {
        $formFactory = $this->getRegisteredInstance('LadocDocumentation\Form\WeightAndDimensionsFormFactory');
        $form = $this->createForm($formFactory);
        $form->bind($weightAndDimenstions);
        return $form;
    }

    protected function getMode() {
        return $this->mode;
    }
}