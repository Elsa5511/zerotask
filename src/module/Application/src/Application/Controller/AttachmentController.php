<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;
use Sysco\Aurora\Stdlib\DateTime;

abstract class AttachmentController extends AbstractBaseController {

    /**
     * this method return the service
     */
    abstract public function getAttachmentService();

    /**
     * this method return the service of the owner
     */
    abstract protected function getOwnerEntityService();

    /**
     * this method return the attachment object and it is set up the owner
     */
    abstract protected function getAttachmentEntityWithOwner($ownerId);

    /**
     * this method return the owner entity path
     */
    abstract protected function getOwnerEntityPath();

    /**
     * this method return the owner controller
     */
    abstract protected function getOwnerController();

    /**
     * this method return the owner entity attribute related 
     */
    abstract protected function getOwnerFieldName();

    /**
     * this method return custom parameters to set up buttons
     */
    abstract protected function getCustomViewParameters();

    private function setAttachmentDataRelated($form) {
        $attachment = $form->getObject();
        $file = $form->get('attachment_form')->get('filename')->getValue();

        if (!empty($file['name'])) {
            $attachmentFileName = $attachment->getFile();
            if (!empty($attachmentFileName)) {
                $this->getAttachmentService()
                        ->removeAttachmentFile($attachmentFileName);
            }
            $fileName = $this->getAttachmentService()
                    ->copyAttachmentFile($file);

            $attachment->setFile($fileName);
            $attachment->setMimeType($file['type']);
        }
        $attachment->setDateAdd(new DateTime('NOW'));
        return $attachment;
    }

    private function saveAttachment($form, $requestPost) {

        $form->setData($requestPost);

        if ($form->isValid()) {
            $attachment = $this->setAttachmentDataRelated($form);

            $customValidationError = $this->customValidationError($requestPost, $attachment);
            if ($customValidationError) {
                $this->sendTranslatedFlashMessage($customValidationError, 'error');
                return false;
            }

            $this->customManageAttachment($requestPost, $attachment);

            $this->getAttachmentService()
                    ->persistAttachment($attachment);

            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("Attachment has been successfully saved."));
            return true;
        }
        return false;
    }

    protected function customValidationError($post, $attachment) {
        return null;
    }

    protected function customManageAttachment($post, $attachment){
        return null;
    }

    public function addAttachmentAction() {
        $this->layout('layout/iframe');
        $ownerId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $attachment = $this->getAttachmentEntityWithOwner($ownerId);


        $form = $this->getAttachmentForm($attachment);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $isValid = $this->saveAttachment($form, $postData);
            if ($isValid) {
                $view = new ViewModel(array('message' => 'ok'));
                $view->setTemplate('application/attachment/edit.phtml');
                return $view;
            }
        }
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('application/attachment/edit.phtml');
        return $view;
    }

    public function editAttachmentAction() {
        $this->layout('layout/iframe');
        $equipmentInstanceAttachmentId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $attachmentInstance = $this->getAttachmentService()->getAttachment($equipmentInstanceAttachmentId);

        if ($attachmentInstance === null) {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }

        $form = $this->getAttachmentForm($attachmentInstance, 'edit');
        $request = $this->getRequest();
        $filename = $attachmentInstance->getFile();

        if ($request->isPost()) {
            $postData = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $isValid = $this->saveAttachment($form, $postData);
            if ($isValid) {
                $view = new ViewModel(array('message' => 'ok'));
                $view->setTemplate('application/attachment/edit.phtml');
                return $view;
            }
        }
        $view = new ViewModel(array('form' => $form, 'filename' => $filename));
        $view->setTemplate('application/attachment/edit.phtml');
        return $view;
    }

    protected function getAttachmentForm($entity, $mode = 'add') {
        $entityPath = $this->getOwnerEntityPath();
        $formFactory = $this->getFormFactory();
        $form = $formFactory->createAttachmentForm($entityPath, $mode);
        $form->bind($entity);

        return $form;
    }

    public function deleteAttachmentAction() {
        $attachmentId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $isDeleted = $this->getAttachmentService()->deleteAttachment($attachmentId);
        if ($isDeleted) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("Attachment has been deleted successfully."));
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("Attachment could not be deleted at this time."), 'error');
            return $this->redirectToReferer();
        }
        $request = $this->getRequest();
        $httpReferer = $request->getServer('HTTP_REFERER');
        return $this->redirect()->toUrl($httpReferer);
    }

    public function deleteManyAttachmentAction() {
        $post = $this->getRequest()->getPost();

        $deleteResults = $this->getAttachmentService()->deleteByIds($post->idList);
        $countDeleted = $deleteResults['deleted'];
        $countFailed = $deleteResults['fails'];

        $formatMessage = $this->getTranslator()->translate('%d attachments have been deleted successfully.');
        $message = sprintf($formatMessage, $countDeleted);
        if ($countDeleted === count($post->idList)) {
            $namespace = 'success';
        } else {
            $formatMessage = $this->getTranslator()->translate('%d attachments could not be deleted');
            $message .= ' ' . sprintf($formatMessage, $countFailed);
            $namespace = ($countFailed === count($post->idList)) ? 'error' : 'warning';
        }

        $this->sendFlashMessage($message, $namespace, true);
        $request = $this->getRequest();

        $httpReferer = $request->getServer('HTTP_REFERER');
        return $this->redirect()->toUrl($httpReferer);
    }

    public function handleAction() {
        $attachmentId = $this->params()->fromRoute('id', 0);

        $attachmentService = $this->getAttachmentService();
        $attachment = $attachmentService->getAttachment($attachmentId);

        if ($attachment !== null) {
            $fileName = $attachment->getFile();
            $filePath = $attachmentService->getAttachmentPath() . $fileName;
            $mimeType = $attachment->getMimeType();

            $response = new \Zend\Http\Response\Stream();
            $response->setStream(fopen($filePath, 'r'));
            $response->setStatusCode(200);

            $howIsOpened = $attachmentService->getHowIsOpened($mimeType);


            $headers = new \Zend\Http\Headers();
            $headers
                    ->addHeaderLine('Content-Type', $mimeType)
                    ->addHeaderLine('Content-Disposition', $howIsOpened . '; filename="' . $fileName . '"')
                    ->addHeaderLine('Content-Length', filesize($filePath));

            $response->setHeaders($headers);
            return $response;
        } else {
            $response = new \Zend\Http\Response();
            $response->setStatusCode(404);
            return $response;
        }
    }

    public function videoHandleAction() {
        $this->layout('layout/iframe');

        $attachmentId = $this->params()->fromRoute('id', false);
        if ($attachmentId) {
            $attachmentService = $this->getAttachmentService();
            $attachment = $attachmentService->getAttachment($attachmentId);

            $fileName = $attachment->getFile();
            $mimeType = $attachment->getMimeType();
            $filePath = $attachmentService->getAttachmentPath() . $fileName;
            $view = new ViewModel(array(
                'fileName' => $fileName,
                'mimeType' => $mimeType,
                'filePath' => $filePath,
            ));
            $view->setTemplate('application/attachment/video.phtml');
            return $view;
        }

        return false;
    }

    public function getAdditionalAttachments()
    {
        return array();
    }

    protected function getLinkMap($attachments)
    {
        $linkMap = array();

        foreach ($attachments as $attachment) {
            $attachmentFile = $attachment->getFile();
            if ($attachment->getLink() !== null && ($attachmentFile === null || empty($attachmentFile))) {
                $link = $attachment->getLink();
                $httpPrefix = 'http://';
                if (substr($link, 0, 7) !== $httpPrefix) {
                    $link = $httpPrefix . $link;
                }
                $linkMap[$attachment->getAttachmentId()] = $link;
            }
        }

        return $linkMap;
    }

    protected function getViewPath()
    {
        return 'application/attachment/attachment-table.phtml';
    }

    public function attachmentTableAction() {

        $ownerId = $this->params()->fromRoute('id', 0);
        $attachmentService = $this->getAttachmentService();
        $ownerFieldName = $this->getOwnerFieldName();
        $attachments = $attachmentService->fetchAttachment(array($ownerFieldName => $ownerId));

        $attachments = array_merge($attachments, $this->getAdditionalAttachments());

        $linkMap = $this->getLinkMap($attachments);

        $defaultsParameters = array(
            'addAction' => 'add-attachment',
            'attachments' => $attachments,
            'id' => $ownerId,
            'editAction' => 'edit-attachment',
            'deleteAction' => 'delete-attachment',
            'ownerEntity' => $this->getOwnerEntityPath(),
            'linkMap' => $linkMap
        );
        $parameters = $this->getCustomViewParameters();
        $newParameters = array_merge($defaultsParameters, $parameters);
        $view = new ViewModel($newParameters);
        $view->setTemplate($this->getViewPath());
        return $view;
    }

}
