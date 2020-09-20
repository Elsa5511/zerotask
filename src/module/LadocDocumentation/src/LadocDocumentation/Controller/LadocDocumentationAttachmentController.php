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

class LadocDocumentationAttachmentController extends AbstractBaseController {
    
    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);

        CustomEventManagerClass::addDescriptionDispatchEvent($events, $this);

        return $this;
    }

    public function indexAction() {
        $documentationId = (int)$this->params()->fromRoute('documentation_id', 0);
        $documentation = $this->getLadocDocumentationService()->findById($documentationId);
        if (!$documentation) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            return $this->redirectToReferer();
        }

        $attachments = $this->getDocumentationAttachmentService()->findByLadocDocumentation($documentationId);

        $viewValues = array(
            'attachments' => $attachments,
            'documentationId' => $documentationId,
            'descriptionInformation' => $documentation->getDescriptionInformation(),
            'buttonConfig' => array(
                'documentationId' => $documentationId,
                'currentPage' => LadocDocumentation::PAGE_DOCUMENTATION_ATTACHMENTS,
                'showPreviousButton' => !$documentation->isComplete()
            )
        );

        $view = new ViewModel($viewValues);
        $view->setTemplate('ladoc-documentation/ladoc-documentation-attachment/index.phtml');

        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $documentation);

        return $view;
    }

    public function addAction() {
        $request = $this->getRequest();

        $documentationAttachment = $this->tryToGetNewAttachment();

        if ($documentationAttachment == null) {
            return $this->redirectToReferer();
        }

        $documentationAttachmentForm = $this->createAndBindForm($documentationAttachment);

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            return $this->storePostData($post, $documentationAttachmentForm, "add");
        } else {
            return $this->displayForm($documentationAttachmentForm);
        }
    }

    public function editAction() {
        $documentationAttachmentId = $this->params()->fromRoute('id', null);
        $documentationAttachment = $this->getDocumentationAttachmentService()->findById($documentationAttachmentId);
        if ($documentationAttachment !== null) {
            $request = $this->getRequest();

            $documentationAttachmentForm = $this->createAndBindForm($documentationAttachment);

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );
                return $this->storePostData($post, $documentationAttachmentForm, "edit");
            } else {
                return $this->displayForm($documentationAttachmentForm, 'edit', $documentationAttachment->getFile());
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $service = $this->getDocumentationAttachmentService();
        $pointId = $this->params()->fromRoute('id', null);
        $ladocAttachment = $service->findById($pointId);
        if ($ladocAttachment !== null) {
            $documentationId = $ladocAttachment->getLadocDocumentation()->getId();
            $service->deleteFile($ladocAttachment);
            $service->remove($ladocAttachment);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'));
            return $this->redirectTo('index', array('documentation_id' => $documentationId));
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $form, $action = 'add') {
        $form->setData($postData);
        $isFormValid = $form->isValid();
        $documentationAttachment = $form->getObject();

        if ($isFormValid) {
            $error = null;
            if ($action == 'edit' && $postData['attachment']['removed_image'] == 1 && empty($postData['attachment']['filename']['tmp_name'])) {
                $error = $this->getTranslator()->translate('The file was not detected');
                return $this->displayForm($form, $action, null, $error);
            }

            $newFile = $this->getDocumentationAttachmentService()
                ->saveAttachment($postData['attachment']['filename'], 1500, $documentationAttachment->getFile());
            if ($newFile)
                $documentationAttachment->setFile($newFile);
            $documentationAttachment->setTitle($documentationAttachment->getDescription());
            $this->saveData($documentationAttachment);
            return $this->redirectToAction($documentationAttachment, 'index');
        } else {
            return $this->displayForm($form, $action);
        }
    }

    protected function saveData($documentationAttachment) {
        $documentationAttachmentId = $this->getDocumentationAttachmentService()->persistData($documentationAttachment);

        if ($documentationAttachmentId > 0) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    protected function tryToGetNewAttachment() {
        try {
            $documentationId = (int)$this->params()->fromRoute('documentation_id', 0);

            $documentationAttachment = $this->getDocumentationAttachmentService()
                ->getNewDocumentationAttachment($documentationId);
            return $documentationAttachment;
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    protected function createAndBindForm($documentationAttachment) {
        $formFactory = $this->getFormFactory('LadocDocumentation', 'DocumentationAttachment');
        if ($documentationAttachment->getPointAttachmentId() > 0)
            $mode = 'edit';
        else
            $mode = 'add';
        $documentationAttachmentForm = $formFactory->createDocumentationAttachmentForm($mode);
        $documentationAttachmentForm->bind($documentationAttachment);
        return $documentationAttachmentForm;
    }

    protected function displayForm($pointForm, $action = 'add', $image = null, $error = null) {
        if ($action == 'add')
            $documentationId = (int)$this->params()->fromRoute('documentation_id', 0);
        else
            $documentationId = $pointForm->getObject()->getLadocDocumentation()->getId();

        $service = $this->getLadocDocumentationService();
        $ladocDocumentation = $service->findById($documentationId);
        if ($ladocDocumentation) {
            BreadcrumbCreator::createAddEditBreadcrumbForDocumentationSubPage($this, $ladocDocumentation);

            $viewValues = array(
                'form' => $pointForm,
                'action' => $action,
                'documentationId' => $documentationId,
                'controllerName' => $this->getControllerName(),
                'image' => $image,
                'imageError' => $error
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'ladoc-documentation/ladoc-documentation-attachment/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function redirectToAction($documentationAttachment, $action) {
        if ($action == 'add' || $action == 'index')
            return $this->redirectTo($action, array('documentation_id' => $documentationAttachment->getLadocDocumentation()->getId()));
        else
            return $this->redirectTo($action, array('id' => $documentationAttachment->getPointAttachmentId()));
    }

    protected function redirectTo($action, $params = array()) {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    public function getControllerName() {
        return "ladoc-documentation-attachment";
    }

    protected function getDocumentationAttachmentService() {
        return $this->getService('LadocDocumentation\Service\LadocDocumentationAttachmentService');
    }

    /**
     * @return LadocDocumentationService
     */
    protected function getLadocDocumentationService() {
        return $this->getService('LadocDocumentation\Service\LadocDocumentation');
    }
}