<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Service\LadocDocumentationService;
use Zend\View\Model\ViewModel;
use Application\Service\ServiceOperationException;
use LadocDocumentation\Controller\Helper\CustomEventManagerClass;
use Zend\EventManager\EventManagerInterface;

class LadocRestraintCertifiedDocumentController extends AbstractBaseController {

    public function indexAction() {
        $restraintCertifiedId = (int)$this->params()->fromRoute('restraint_certified_id', 0);
        $type = $this->params()->fromRoute('type', '');
        $restraintCertified = $this->getLadocRestraintCertifiedService()->findById($restraintCertifiedId);
        if (!$restraintCertified) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            return $this->redirectToReferer();
        }
        if (!$type) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            return $this->redirectToReferer();
        }

        $documents = $this->getRestraintCertifiedDocumentService()->findByRestraintCertified($restraintCertifiedId);

        if($type == 'load')
            $ladocDocumentation = $restraintCertified->getLoadDocumentation();
        else
            $ladocDocumentation = $restraintCertified->getCarrierDocumentation();
        BreadcrumbCreator::createBreadcrumbForDocumentationDisplay($this, $ladocDocumentation);

        $viewValues = array(
            'documents' => $documents,
            'restraintCertified' => $restraintCertified,
            'documentation' => $type == 'load' ? $restraintCertified->getCarrierDocumentation() : $restraintCertified->getLoadDocumentation(),
            'type' => $type,
            'documentationId' => $ladocDocumentation->getId()
        );

        $view = new ViewModel($viewValues);
        $view->setTemplate('ladoc-documentation/restraint-certified-document/index.phtml');

        return $view;
    }

    public function addAction() {
        $request = $this->getRequest();

        $restraintCertifiedDocument = $this->tryToGetNewDocument();

        if ($restraintCertifiedDocument == null) {
            return $this->redirectToReferer();
        }

        $restraintCertifiedDocumentForm = $this->createAndBindForm($restraintCertifiedDocument);

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            return $this->storePostData($post, $restraintCertifiedDocumentForm, "add");
        } else {
            return $this->displayForm($restraintCertifiedDocumentForm);
        }
    }

    public function editAction() {
        $restraintCertifiedDocumentId = $this->params()->fromRoute('id', null);
        $restraintCertifiedDocument = $this->getRestraintCertifiedDocumentService()->findById($restraintCertifiedDocumentId);
        if ($restraintCertifiedDocument !== null) {
            $request = $this->getRequest();

            $restraintCertifiedDocumentForm = $this->createAndBindForm($restraintCertifiedDocument);

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );
                return $this->storePostData($post, $restraintCertifiedDocumentForm, "edit");
            } else {
                return $this->displayForm($restraintCertifiedDocumentForm, 'edit', $restraintCertifiedDocument->getFile());
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $service = $this->getRestraintCertifiedDocumentService();
        $pointId = $this->params()->fromRoute('id', null);
        $restraintDocument = $service->findById($pointId);
        if ($restraintDocument !== null) {
            //$restraintCertifiedId = $restraintDocument->getLadocRestraintCertified()->getId();
            $service->deleteFile($restraintDocument);
            $service->remove($restraintDocument);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'));
            /*return $this->redirectTo('index', array(
                'documentation_id' => $documentationId
            ));*/
            return $this->redirectToReferer();
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $form, $action = 'add') {
        $form->setData($postData);
        $isFormValid = $form->isValid();
        $restraintCertifiedDocument = $form->getObject();

        if ($isFormValid) {
            $error = null;
            if ($action == 'edit' && $postData['attachment']['removed_image'] == 1 && empty($postData['attachment']['filename']['tmp_name'])) {
                $error = $this->getTranslator()->translate('The file was not detected');
                return $this->displayForm($form, $action, null, $error);
            }

            $newFile = $this->getRestraintCertifiedDocumentService()
                ->saveDocument($postData['attachment']['filename'], 1500, $restraintCertifiedDocument->getFile());
            if ($newFile)
                $restraintCertifiedDocument->setFile($newFile);
            $this->saveData($restraintCertifiedDocument);
            return $this->redirectToAction($restraintCertifiedDocument, 'index', $this->params()->fromRoute('type', ''));
        } else {
            return $this->displayForm($form, $action);
        }
    }

    protected function saveData($restraintCertifiedDocument) {
        $restraintCertifiedDocumentId = $this->getRestraintCertifiedDocumentService()->persistData($restraintCertifiedDocument);

        if ($restraintCertifiedDocumentId > 0) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    protected function tryToGetNewDocument() {
        try {
            $restraintCertifiedId = (int)$this->params()->fromRoute('restraint_certified_id', 0);

            $restraintCertifiedDocument = $this->getRestraintCertifiedDocumentService()
                ->getNewRestraintCertifiedDocument($restraintCertifiedId);
            return $restraintCertifiedDocument;
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    protected function createAndBindForm($restraintCertifiedDocument) {
        $formFactory = $this->getFormFactory('LadocDocumentation', 'RestraintDocumentation');
        if ($restraintCertifiedDocument->getPointAttachmentId() > 0)
            $mode = 'edit';
        else
            $mode = 'add';
        $restraintCertifiedDocumentForm = $formFactory->createRestraintCertifiedDocumentForm($mode);
        $restraintCertifiedDocumentForm->bind($restraintCertifiedDocument);
        return $restraintCertifiedDocumentForm;
    }

    protected function displayForm($pointForm, $action = 'add', $image = null, $error = null) {
        if ($action == 'add')
            $restraintCertifiedId = (int)$this->params()->fromRoute('restraint_certified_id', 0);
        else
            $restraintCertifiedId = $pointForm->getObject()->getLadocRestraintCertified()->getId();

        $service = $this->getLadocRestraintCertifiedService();
        $restraintCertified = $service->findById($restraintCertifiedId);
        if ($restraintCertified) {
            $type = $this->params()->fromRoute('type', '');
            BreadcrumbCreator::createBreadcrumbForRestraintCertifiedDocument($this, $restraintCertified, $type);

            $viewValues = array(
                'form' => $pointForm,
                'action' => $action,
                'restraintCertifiedId' => $restraintCertifiedId,
                'controllerName' => $this->getControllerName(),
                'image' => $image,
                'imageError' => $error,
                'type' => $type
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'ladoc-documentation/restraint-certified-document/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function redirectToAction($restraintCertifiedDocument, $action, $type) {
        if ($action == 'add' || $action == 'index')
            return $this->redirectTo($action, array(
                    'restraint_certified_id' => $restraintCertifiedDocument->getLadocRestraintCertified()->getId(),
                    'type' => $type
                )
            );
        else
            return $this->redirectTo($action, array('id' => $documentationAttachment->getPointAttachmentId()));
    }

    protected function redirectTo($action, $params = array()) {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    public function getControllerName() {
        return "ladoc-restraint-certified-document";
    }

    protected function getRestraintCertifiedDocumentService() {
        return $this->getService('LadocDocumentation\Service\RestraintCertifiedDocumentService');
    }

    /**
     * @return LadocDocumentationService
     */
    protected function getLadocRestraintCertifiedService() {
        return $this->getService('LadocDocumentation\Service\RestraintCertifiedService');
    }
}