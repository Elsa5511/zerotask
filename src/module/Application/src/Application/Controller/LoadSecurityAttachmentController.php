<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class LoadSecurityAttachmentController extends AbstractBaseController 
{

    public function indexAction()
    {
        $this->setBreadcrumbForApplication();

        $calculatorInfo = $this->getCalculatorInfoService()->getData();
        $view = new ViewModel(array(
            'attachments' => $calculatorInfo->getAttachments()
        ));
        return $view;
    }

    public function addAction() {
        $request = $this->getRequest();

        $attachment = $this->tryToGetNewAttachment();

        if ($attachment == null) {
            return $this->redirectToReferer();
        }

        $attachmentForm = $this->createAndBindForm($attachment);

        if ($request->isPost()) {
            $post = array_merge_recursive(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            return $this->storePostData($post, $attachmentForm, "add");
        } else {
            return $this->displayForm($attachmentForm);
        }
    }

    public function editAction() {
        $attachmentId = $this->params()->fromRoute('id', null);
        $attachment = $this->getCalculatorInfoService()->findAttachmentById($attachmentId);
        if ($attachment !== null) {
            $request = $this->getRequest();

            $attachmentForm = $this->createAndBindForm($attachment);

            if ($request->isPost()) {
                $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );
                return $this->storePostData($post, $attachmentForm, "edit");
            } else {
                return $this->displayForm($attachmentForm, 'edit', $attachment->getFile());
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $service = $this->getCalculatorInfoService();
        $attachmentId = $this->params()->fromRoute('id', null);
        $attachment = $service->findAttachmentById($attachmentId);
        if ($attachment !== null) {
            $service->deleteFile($attachment);
            $service->remove($attachment);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'));
            return $this->redirectToPath('load-security-attachment', 'index');
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $form, $action = 'add') {
        $form->setData($postData);
        $isFormValid = $form->isValid();
        $attachment = $form->getObject();

        if ($isFormValid) {
            $error = null;
            if ($action == 'edit' && $postData['attachment']['removed_image'] == 1 && empty($postData['attachment']['filename']['tmp_name'])) {
                $error = $this->getTranslator()->translate('The file was not detected');
                return $this->displayForm($form, $action, null, $error);
            }

            $newFile = $this->getCalculatorInfoService()
                ->saveAttachment($postData['attachment']['filename'], 1500, $attachment->getFile());
            if ($newFile)
                $attachment->setFile($newFile);
            $this->saveData($attachment);
            return $this->redirectToPath('load-security-attachment', 'index');
        } else {
            return $this->displayForm($form, $action);
        }
    }

    protected function saveData($attachment) {
        $attachmentId = $this->getCalculatorInfoService()->persistAttachment($attachment);

        if ($attachmentId > 0) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    protected function tryToGetNewAttachment() {
        try {
            $attachment = $this->getCalculatorInfoService()
                ->getNewAttachment();
            return $attachment;
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    protected function createAndBindForm($attachment) {
        $formFactory = $this->getFormFactory('Documentation');
        if ($attachment->getId() > 0)
            $mode = 'edit';
        else
            $mode = 'add';
        $attachmentForm = $formFactory->createCalculatorAttachmentForm($mode);
        $attachmentForm->bind($attachment);
        return $attachmentForm;
    }

    protected function displayForm($attachmentForm, $action = 'add', $image = null, $error = null) {
        $service = $this->getCalculatorInfoService();
        $calculatorInfo = $service->getData();
        if ($calculatorInfo) {
            $this->setBreadcrumbForApplication();

            $viewValues = array(
                'form' => $attachmentForm,
                'action' => $action,
                'controllerName' => 'load-security-attachment',
                'image' => $image,
                'imageError' => $error
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'application/load-security-attachment/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    /**
     * @return \Documentation\Service\CalculatorInfoService
     */
    private function getCalculatorInfoService()
    {
        return $this->getService('Documentation\Service\CalculatorInfoService');
    }
}