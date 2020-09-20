<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;

abstract class SectionController extends AbstractBaseController
{

    /**
     * this method return the service
     */
    abstract public function getSectionService();

    /**
     * this method return the service of the owner
     */
    abstract protected function getOwnerEntityService();

    /**
     * this method return the section object and it is set up the owner
     */
    abstract protected function getSectionEntityWithOwner($ownerId);

    /**
     * this method return the owner entity path
     */
    abstract protected function getOwnerEntityPath();

    /**
     * this method return the owner controller
     */
    abstract protected function getOwnerController();

    /**
     * this method return the owner fieldname
     */
    abstract protected function getOwnerFieldName();

    /**
     * this method return the action that sould be redirect after delete
     */
    abstract protected function actionAfterDelete();

    public function addSectionAction()
    {
        $this->layout('layout/iframe');
        $ownerId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $sectionIdPassed = $this->getEvent()->getRouteMatch()->getParam('sectionId', false);

        $section = $this->getSectionEntityWithOwner($ownerId);

        if ($section === null) {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
        
        $form = $this->getSectionForm($section);

        if ($sectionIdPassed) {
            $form->get('section_form')->get('parent')->setValue($sectionIdPassed);
        }
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            $isValid = $this->isValidSection($form, $postData);
            if ($isValid) {
                $view = new ViewModel(array('message' => 'ok'));
                $view->setTemplate('application/section/edit.phtml');
                return $view;
            }
        }
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('application/section/edit.phtml');
        return $view;
    }

    public function editSectionAction()
    {
        $this->layout('layout/iframe');
        $sectionId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $section = $this->getSectionService()->getSection($sectionId);
        
        if ($section === null) {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }

        $form = $this->getSectionForm($section, 'edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $isValid = $this->isValidSection($form, $postData);
            if ($isValid) {
                $view = new ViewModel(array('message' => 'ok'));
                $view->setTemplate('application/section/edit.phtml');
                return $view;
            }
        }
        $view = new ViewModel(array('form' => $form));
        $view->setTemplate('application/section/edit.phtml');
        return $view;
    }

    public function getSectionForm($entity, $mode = 'add')
    {
        $entityPath = $this->getOwnerEntityPath();
        $formFactory = $this->getServiceLocator()->get('\Application\Form\FormFactory');

        $parentOptions = $this->getSectionService()
                ->getParentOptionsArray($this->getOwnerFieldName(), $entity->getOwner(), $entity);


        $form = $formFactory->createSectionForm($entityPath, $parentOptions, $mode);
        $form->bind($entity);

        return $form;
    }

    public function deleteSectionAction()
    {
        $sectionId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $referenceId = $this->getEvent()->getRouteMatch()->getParam('referenceId', false);


        $deleteResult = $this->getSectionService()->deleteSection($sectionId);

        $this->sendFlashMessage($deleteResult['message'], $deleteResult['namespace'], true);

        return $this->redirect()->toRoute('base/wildcard', array(
                    'application' => $this->params()->fromRoute('application'),
                    'controller' => $this->getOwnerController(),
                    'action' => $this->actionAfterDelete(),
                    'id' => $referenceId
        ));
    }

    private function isValidSection($form, $requestPost)
    {

        $form->setData($requestPost);

        if ($form->isValid()) {
            $section = $form->getObject();

            $this->getSectionService()
                    ->persistSection($section);

            $message = $this->getTranslator()->translate(
                    'The Section has been saved successfully.');
            $nameSpace = 'success';

            $this->sendFlashMessage($message, $nameSpace, true);
            
            
            return true;
        }
        return false;
    }

}