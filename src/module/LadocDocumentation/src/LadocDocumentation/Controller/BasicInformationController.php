<?php

namespace LadocDocumentation\Controller;


use Application\Controller\AbstractBaseController;
use Application\Service\ServiceOperationException;
use Application\Utility\Image;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Service\BasicInformationService;
use Zend\View\Model\ViewModel;

abstract class BasicInformationController extends AbstractBaseController {

    const IMAGE_PATH = 'public/content/ladoc-documentation/';
    const IMAGE_REMOVED = 1;

    private $REDIRECT_WIZARD = 1;
    private $REDIRECT_DISPLAY = 2;

    protected abstract function createBasicInformationForm($formFactory);
    protected abstract function getControllerName();

    /**
     * @return BasicInformationService
     */
    protected abstract function getBasicInformationService();

    public function addAction() {
        $documentationId = $this->params()->fromRoute('documentation_id', 0);
        $existingBasicInformation = $this->getExistingBasicInformation($documentationId);
        if ($existingBasicInformation) {
            return $this->redirectToPath($this->getControllerName(), 'edit-wizard', array(
                'id' => $existingBasicInformation->getId(),
            ));
        }
        $basicInformation = $this->tryToGetNewBasicInformation($documentationId);

        if ($basicInformation) {
            BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $basicInformation->getLadocDocumentation());
            $form = $this->createAndBindBasicInformationForm($basicInformation);

            if ($this->getRequest()->isPost()) {
                return $this->addEditPostAction($form, $this->REDIRECT_WIZARD);
            }
            else {
                return $this->setupViewModel($form, $basicInformation->getImage(), $this->REDIRECT_WIZARD);
            }
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }


    public function editAction() {
        return $this->editActionInternal($this->REDIRECT_DISPLAY);
    }

    public function editWizardAction() {
        return $this->editActionInternal($this->REDIRECT_WIZARD);
    }

    private function editActionInternal($redirect) {
        $id = $this->params()->fromRoute('id', 0);
        $service = $this->getBasicInformationService();
        $basicInformation = $service->findById($id);
        if ($basicInformation) {
            BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $basicInformation->getLadocDocumentation());
            $form = $this->createAndBindBasicInformationForm($basicInformation);

            if ($this->getRequest()->isPost()) {
                return $this->addEditPostAction($form, $redirect);
            }
            else {
                return $this->setupViewModel($form, $basicInformation->getImage(), $redirect);
            }
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function addEditPostAction($form, $redirect) {
        $post = array_merge_recursive($this->request->getPost()->toArray(),
            $this->request->getFiles()->toArray());

        $this->handleEmptyMultiselects($post);

        $form->setData($post);
        if ($form->isValid()) {
            $basicInformation = $form->getObject();
            $this->handleImage($post, $basicInformation);

            $service = $this->getBasicInformationService();
            $service->persist($basicInformation);

            if ($redirect === $this->REDIRECT_WIZARD) {
                return $this->redirectToPath(
                    'ladoc-documentation', 'wizard', array(
                    'id' => $basicInformation->getLadocDocumentation()->getId(),
                    'current_page' => LadocDocumentation::PAGE_BASIC_INFORMATION,
                    'direction' => LadocDocumentation::DIRECTION_NEXT
                ));
            }
            else if ($redirect === $this->REDIRECT_DISPLAY) {
                return $this->redirectToPath(
                    'ladoc-documentation', 'display', array(
                    'id' => $basicInformation->getLadocDocumentation()->getId()
                ));
            }
        }
        else {
            $this->sendTranslatedFlashMessage($form->getMessages(), 'failure');
            return $this->setupViewModel($form, null, $redirect);
        }
    }

    // Doctrine doesn't automatically clear removal of multiple selections,
    // as the posted values don't include these fields.
    // https://github.com/doctrine/DoctrineModule/issues/215
    private function handleEmptyMultiselects(&$post) {
        $basicInfoPost = &$post['basic-information'];
        $selectorIndexes = array('approvedFormsOfTransportation', 'stanags');

        foreach ($selectorIndexes as $selectorIndex) {
            if (!array_key_exists($selectorIndex, $basicInfoPost)) {
                $basicInfoPost[$selectorIndex] = array();
            }
        }
    }

    private function getExistingBasicInformation($documentationId) {
        $service = $this->getBasicInformationService();
        $basicInformation = $service->findByDocumentationId($documentationId);
        return $basicInformation;
    }

    private function tryToGetNewBasicInformation($documentationId) {
        try {
            $service = $this->getBasicInformationService();
            return $service->createNewBasicInformation($documentationId);
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    private function setupViewModel($form, $image = null, $redirect) {
        $viewModel = new ViewModel(array(
            'form' => $form,
            'image' => $image,
            'showBackLink' => $redirect === $this->REDIRECT_DISPLAY
        ));
        $viewModel->setTemplate('ladoc-documentation/basic-information/add-edit');
        return $viewModel;
    }


    private function createAndBindBasicInformationForm($basicInformation) {
        $formFactory = $this->getRegisteredInstance('LadocDocumentation\Form\BasicInformationFormFactory');
        $form = $this->createBasicInformationForm($formFactory);
        $form->bind($basicInformation);
        return $form;
    }

    private function handleImage($post, $entity) {
        if ($post["basic-information"]["remove-image"] == self::IMAGE_REMOVED) {
            $image = new Image();
            $image->deleteImage(self::IMAGE_PATH . $entity->getImage());
            $entity->setImage(null);
        }
        else {
            $imageData = $post["basic-information"]["image-data"];
            $newImageHasBeenUploaded = !empty($imageData['tmp_name']);
            if ($newImageHasBeenUploaded) {
                $image = new Image();
                $image->deleteImage(self::IMAGE_PATH . $entity->getImage());
                $newImage = $image->resizeImage($imageData['tmp_name'], 1500,
                    self::IMAGE_PATH . $imageData['name']);
                $entity->setImage($newImage);
            }
        }
    }
}


