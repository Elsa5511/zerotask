<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Entity\Point;
use LadocDocumentation\Service\LadocDocumentationService;
use Zend\View\Model\ViewModel;
use Application\Service\ServiceOperationException;
use Zend\Stdlib\Parameters;
use LadocDocumentation\Controller\Helper\CustomEventManagerClass;
use Zend\EventManager\EventManagerInterface;

abstract class PointBaseController extends AbstractBaseController
{
	protected abstract function getNewPoint();

    protected abstract function createPointForm($formFactory);

    protected abstract function getPointService();

    public abstract function getControllerName();

    protected abstract function getCollectionAttachmentsIndex();

    protected abstract function redirectToAction($point, $action);

    protected abstract function getViewTitles();

    protected abstract function getCurrentPage();

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return AbstractBaseController
     */
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);

        CustomEventManagerClass::addDescriptionDispatchEvent($events, $this);

        return $this;
    }

    public function indexAction()
    {
        $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        $documentation = $this->getLadocDocumentationService()->findById($documentationId);
        if(!$documentation) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            return $this->redirectToReferer();
        }
        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $documentation);
        $pointService = $this->getPointService();
        $points = $pointService->findByLadocDocumentation($documentationId);
        $titles = $this->getViewTitles();
        $title = $titles['indexTitle'];

        $viewValues = array(
            'points' => $points,
            'title' => $title,
            'documentationId' => $documentationId,
            'descriptionInformation' => $documentation->getDescriptionInformation(),
            'buttonConfig' => array(
                'documentationId' => $documentationId,
                'currentPage' => $this->getCurrentPage(),
                'showPreviousButton' => !$documentation->isComplete()
            )
        );

        $view = new ViewModel($viewValues);
        $view->setTemplate('ladoc-documentation/' . $pointService->getContentDirname() . '/index.phtml');
        return $view;
    }

	public function addAction()
    {
    	$request = $this->getRequest();

        $point = $this->tryToGetNewPoint();

        if ($point == null) {
            return $this->redirectToReferer();
        }

        $pointForm = $this->createAndBindPointForm($point);

        if ($request->isPost()) {
            $post = $this->getPointService()->mergeWithAttachments(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            $postObj = new Parameters();
            $postObj->fromArray($post);
            return $this->storePostData($postObj, $pointForm, "add");
        } else {
            return $this->displayForm($pointForm);
        }
    }

    public function editAction()
    {
        $pointId = $this->params()->fromRoute('id', null);
        $point = $this->getPointService()->findById($pointId);
        if ($point !== null) {

            $pointForm = $this->createAndBindPointForm($point);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $this->getPointService()->mergeWithAttachments(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );

                $postObj = new Parameters();
                $postObj->fromArray($post);
                return $this->storePostData($postObj, $pointForm, "edit");
            } else {
                return $this->displayForm($pointForm, 'edit', $point);
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $service = $this->getPointService();
        $pointId = $this->params()->fromRoute('id', null);
        $point = $service->findById($pointId);
        if ($point !== null) {
            $documentationId = $point->getLadocDocumentation()->getId();
            $service->deleteAttachments($point->getAttachments());
            $service->remove($point);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'));
            return $this->redirectTo('index', array('documentation_id' => $documentationId));
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $pointForm, $action = 'add')
    {
        $pointForm->setData($postData);
        $isFormValid = $pointForm->isValid();
        $point = $pointForm->getObject();

        $isCustomValid = true;
        $error = null;
        if($action == 'edit') {
            $isCustomValid = $this->getPointService()->
                            validateFormCustom($postData, $this->getCollectionAttachmentsIndex(), $error);

            $messages = $pointForm->getInputFilter()->getMessages();
            if(!isset($postData['point'][$this->getCollectionAttachmentsIndex()]) && count($messages) == 0) {
                $isFormValid = true;
                $point->removeAttachments();
            }
        }

        if ($isFormValid && $isCustomValid) {
            $this->getPointService()->saveAttachmentsFiles($point, $postData);
            $this->savePointData($point);
            return $this->redirectToAction($point, 'index');
        } else {
            return $this->displayForm($pointForm, $action, $point, $error);
        }
    }

    protected function savePointData($point)
    {
        $pointId = $this->getPointService()->persistData($point);

        if ($pointId > 0) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    /**
     * @return Point
     */
    protected function tryToGetNewPoint()
    {
        try {
            return $this->getNewPoint();
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    protected function createAndBindPointForm($point)
    {
        $formFactory = $this->getFormFactory('LadocDocumentation', 'Point');
        if($point->getId() > 0)
            $formFactory->setMode('edit');
        $pointForm = $this->createPointForm($formFactory);
        $pointForm->bind($point);
        return $pointForm;
    }

    protected function displayForm($pointForm, $action = 'add', $point = null, $error = null)
    {
        $titles = $this->getViewTitles();
        $title = $titles["{$action}Title"];

        if($action == 'add')
            $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        else
            $documentationId = $point->getLadocDocumentation()->getId();

        $ladocDocumentationService = $this->getLadocDocumentationService();
        $ladocDocumentation = $ladocDocumentationService->findById($documentationId);
        if ($ladocDocumentation) {
            BreadcrumbCreator::createAddEditBreadcrumbForDocumentationSubPage($this, $ladocDocumentation);

            $viewValues = array(
                'form' => $pointForm,
                'action' => $action,
                'point' => $point,
                'controllerName' => $this->getControllerName(),
                'customError' => $error,
                'title' => $title,
                'documentationId' => $documentationId
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'ladoc-documentation/point/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }

    }

    protected function redirectTo($action, $params = array())
    {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    /**
     * @return LadocDocumentationService
     */
    protected function getLadocDocumentationService()
    {
        return $this->getService('LadocDocumentation\Service\LadocDocumentation');
    }
}