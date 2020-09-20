<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Entity\LadocRestraintCertified;
use LadocDocumentation\Form\RestraintDocumentationFormFactory;
use LadocDocumentation\Service\LadocDocumentationService;
use LadocDocumentation\Service\RestraintCertifiedService;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Parameters;

abstract class RestraintCertifiedController extends AbstractBaseController
{
    protected abstract function getType();

    protected abstract function getControllerName();

    public function indexAction()
    {
        $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        $documentation = $this->getLadocDocumentationService()->findById($documentationId);
        if(!$documentation) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            return $this->redirectToReferer();
        }

        $restraintCertifiedService = $this->getRestraintCertifiedService();
        $entities = $restraintCertifiedService->findByDocumentation($documentationId, $this->getType());
        $templateType = null;
        if($this->getType() == "load") {
            $filteredEntities = array();
            $templateType = (int) $this->params()->fromRoute('template_type', EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD);
            foreach ($entities as $ent) {
                if($templateType == $ent->getCarrierDocumentation()->getLowestTaxonomyTemplateType()) {
                    $filteredEntities[] = $ent;
                }
            }
            $entities = $filteredEntities;
        }

        BreadcrumbCreator::createBreadcrumbForDocumentationSubPage($this, $documentation);
        $viewValues = array(
            'entities' => $entities,
            'documentationId' => $documentationId,
            'type' => $this->getType(),
            'templateType' => $templateType
        );

        $view = new ViewModel($viewValues);
        $view->setTemplate('ladoc-documentation/restraint-certified/index.phtml');
        return $view;
    }

    public function addAction()
    {
        $request = $this->getRequest();

        $entity = $this->getNewEntity();

        if ($entity == null) {
            return $this->redirectToReferer();
        }

        $restraintCertifiedForm = $this->createAndBindForm($entity, $this->getType());

        if ($request->isPost()) {
            $post = $this->getRestraintCertifiedService()->mergeWithAttachments(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );

            $postObj = new Parameters();
            $postObj->fromArray($post);
            return $this->storePostData($postObj, $restraintCertifiedForm, "add");
        } else {
            return $this->displayForm($restraintCertifiedForm);
        }
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', null);
        $entity = $this->getRestraintCertifiedService()->findById($id);
        if ($entity !== null) {
            $restraintCertifiedForm = $this->createAndBindForm($entity, $this->getType());
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $this->getRestraintCertifiedService()->mergeWithAttachments(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
                );

                $postObj = new Parameters();
                $postObj->fromArray($post);
                return $this->storePostData($postObj, $restraintCertifiedForm, "edit");
            } else {
                return $this->displayForm($restraintCertifiedForm, 'edit', $entity);
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id', null);
        $entity = $this->getRestraintCertifiedService()->findById($id);
        if ($entity !== null) {
            $service = $this->getLadocDocumentationService();
            $loadDocumentation = $service->findById($entity->getLoadDocumentation());
            $carrierDocumentation = $service->findById($entity->getCarrierDocumentation());

            $templateType = $service->getLowestTaxonomyTemplateType($carrierDocumentation);

            $title = $entity->getTitle($this->translate('on'));
            BreadcrumbCreator::createDetailBreadcrumbForDocumentationSubPage($this, $this->getDocumentationByType($entity), $title);
            $viewModel = new ViewModel(array(
                'restraintDocumentation' => $entity,
                'loadDocumentation' => $loadDocumentation,
                'carrierDocumentation' => $carrierDocumentation,
                'type' => $this->getType(),
                'templateType' => $templateType
            ));
            $viewModel->setTemplate('ladoc-documentation/restraint-certified/detail.phtml');
            return $viewModel;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    //TODO: in the future change the request to POST
    public function deleteAction()
    {
        $service = $this->getRestraintCertifiedService();
        $id = $this->params()->fromRoute('id', null);
        $entity = $service->findById($id);
        if ($entity !== null) {
            if($this->getType() == 'load')
                $documentationId = $entity->getLoadDocumentation()->getId();
            else
                $documentationId = $entity->getCarrierDocumentation()->getId();
            $service->deleteAttachments($entity->getAttachments());
            $service->deleteImage($entity);
            $service->remove($entity);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'));
            return $this->redirectTo('index', array('documentation_id' => $documentationId));
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $restraintCertifiedForm, $action = 'add')
    {
        $restraintCertifiedForm->setData($postData);
        $isFormValid = $restraintCertifiedForm->isValid();
        $entity = $restraintCertifiedForm->getObject();
        $service = $this->getRestraintCertifiedService();

        $isCustomValid = true;
        $error = null;
        if($action == 'edit') {
            $isCustomValid = $service->validateFormCustom($postData, $this->getCollectionAttachmentsIndex(), $error);

            $messages = $restraintCertifiedForm->getInputFilter()->getMessages();
            if($this->checkIfAllAttachmentsWereRemovedForEdit($postData, $messages)) {
                $isFormValid = true;
                $entity->removeAttachments();
            }
        }

        if ($isFormValid && $isCustomValid) {
            $service->setNullDates($entity, $postData);
            $service->saveImage($entity, $postData);

            $service->saveAttachmentsFiles($entity, $postData);

            $currentUserId = $this->getCurrenUser()->getId();
            
            $entity->setCreatedBy($currentUserId);
            $entity->setApprovedBy($currentUserId);
            $this->saveData($entity);
            return $this->redirectToAction($entity, 'index');
        } else {

            return $this->displayForm($restraintCertifiedForm, $action, $entity, $error);
        }
    }

    /**
     * This verification is because when there is an existent attachment in database, and then you remove it,
     * the form validate it as a false, but this wouldn't happen, so this function check if this exceptions case happens
     */
    private function checkIfAllAttachmentsWereRemovedForEdit($postData, $messages)
    {
        return !isset($postData['point'][$this->getCollectionAttachmentsIndex()]) &&
        isset($messages['point']) && count($messages['point']) == 1 &&
        isset($messages['point'][$this->getCollectionAttachmentsIndex()]);
    }

    protected function saveData($restraintCertified)
    {
        $id = $this->getRestraintCertifiedService()->persistData($restraintCertified);

        if ($id > 0) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    /**
     * @param LadocRestraintCertified $entity
     * @param $type
     * @return mixed
     */
    protected function createAndBindForm($entity, $type)
    {
        /**
         * @var $formFactory RestraintDocumentationFormFactory
         */
        $formFactory = $this->getFormFactory('LadocDocumentation', 'RestraintDocumentation');
        $mode = 'add';

        if($entity->getId() > 0)
            $mode = 'edit';

        if($type == "load") {
            if($mode == "add")
                $templateType = (int) $this->params()->fromRoute('template_type', EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD);
            else
                $templateType = $entity->getCarrierDocumentation()->getLowestTaxonomyTemplateType();
        } else {
            $documentation = $entity->getCarrierDocumentation();
            $templateType = $this->getLadocDocumentationService()->getLowestTaxonomyTemplateType($documentation);
        }

        $restraintCertifiedForm = $formFactory->createRestraintCertifiedForm($mode, $type, $templateType);
        $restraintCertifiedForm->bind($entity);
        return $restraintCertifiedForm;
    }

    protected function displayForm($restraintCertifiedForm, $action = 'add', $entity = null, $error = null)
    {
        if($action == 'add')
            $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        else
            $documentationId = $this->getDocumentationByType($entity)->getId();


        $service = $this->getLadocDocumentationService();
        $documentation = $service->findById($documentationId);

        if ($documentation) {
            $templateTypeBreadcrumb = null;
            if ($this->getType() == "load") {
                if ($action == "add")
                    $templateType = (int)$this->params()->fromRoute('template_type', EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD);
                else
                    $templateType = $entity->getCarrierDocumentation()->getLowestTaxonomyTemplateType();

                $templateTypeBreadcrumb = $templateType;
            } else
                $templateType = $service->getLowestTaxonomyTemplateType($documentation);

            BreadcrumbCreator::createAddEditBreadcrumbForDocumentationSubPage($this, $documentation, $templateTypeBreadcrumb);

            $viewValues = array(
                'form' => $restraintCertifiedForm,
                'action' => $action,
                'entity' => $entity,
                'controllerName' => $this->getControllerName(),
                'customError' => $error,
                'documentationId' => $documentationId,
                'type' => $this->getType(),
                'templateType' => $templateType
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'ladoc-documentation/restraint-certified/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    /**
     * @return \LadocDocumentation\Entity\LadocRestraintCertified
     */
    protected function getNewEntity()
    {
        $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        $ladocDocumentation = $this->getLadocDocumentationService()->findById($documentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $restraintCertified = $this->getRestraintCertifiedService()
            ->getNewEntity($ladocDocumentation);

        return $restraintCertified;
    }

    private function getDocumentationByType($restraintCertified)
    {
        if($this->getType() == 'load')
            return $restraintCertified->getLoadDocumentation();
        else
            return $restraintCertified->getCarrierDocumentation();
    }

    protected function getCollectionAttachmentsIndex()
    {
        return 'ladocRestraintCertifiedAttachments';
    }

    protected function redirectToAction($restraintCertified, $action)
    {
        if($action == 'add' || $action == 'index') {
            $params = array('documentation_id' => $this->getDocumentationByType($restraintCertified)->getId());
            if($this->getType() == "load" && $action == "index")
                $params['template_type'] = $restraintCertified->getCarrierDocumentation()->getLowestTaxonomyTemplateType();
            return $this->redirectTo($action, $params);
        }
        else {
            return $this->redirectTo($action, array('id' => $restraintCertified->getId()));
        }
    }

    protected function redirectTo($action, $params = array())
    {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    /**
     * @return RestraintCertifiedService
     */
    protected function getRestraintCertifiedService()
    {
        return $this->getService('LadocDocumentation\Service\RestraintCertifiedService');
    }

    /**
     * @return LadocDocumentationService
     */
    protected function getLadocDocumentationService()
    {
        return $this->getService('LadocDocumentation\Service\LadocDocumentation');
    }
}