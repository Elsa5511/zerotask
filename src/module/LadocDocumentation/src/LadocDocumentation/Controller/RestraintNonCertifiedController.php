<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;
use LadocDocumentation\Controller\Helper\BreadcrumbCreator;
use LadocDocumentation\Entity\LadocRestraintNonCertified;
use LadocDocumentation\Form\RestraintDocumentationFormFactory;
use LadocDocumentation\Service\LadocDocumentationService;
use Zend\View\Model\ViewModel;

abstract class RestraintNonCertifiedController extends AbstractBaseController
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

        $restraintNonCertifiedService = $this->getRestraintNonCertifiedService();
        $entities = $restraintNonCertifiedService->findByDocumentation($documentationId, $this->getType());

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
        $view->setTemplate('ladoc-documentation/restraint-non-certified/index.phtml');
        return $view;
    }

    public function addAction()
    {
        $request = $this->getRequest();

        $entity = $this->getNewEntity();

        if ($entity == null) {
            return $this->redirectToReferer();
        }

        $restraintNonCertifiedForm = $this->createAndBindForm($entity);

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            return $this->storePostData($post, $restraintNonCertifiedForm, "add");
        } else {
            return $this->displayForm($restraintNonCertifiedForm);
        }
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('id', null);
        $entity = $this->getRestraintNonCertifiedService()->findById($id);
        if ($entity !== null) {
            $restraintNonCertifiedForm = $this->createAndBindForm($entity);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost()->toArray();
                return $this->storePostData($post, $restraintNonCertifiedForm, "edit");
            } else {
                return $this->displayForm($restraintNonCertifiedForm, $entity, 'edit');
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id', null);
        $entity = $this->getRestraintNonCertifiedService()->findById($id);
        if ($entity !== null) {
            BreadcrumbCreator::createDetailBreadcrumbForDocumentationSubPage($this, $this->getDocumentationByType($entity), $entity->getTitle($this->translate('on')));

            $viewModel = new ViewModel(array('entity' => $entity, 'type' => $this->getType()));
            $viewModel->setTemplate('ladoc-documentation/restraint-non-certified/detail.phtml');
            return $viewModel;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $service = $this->getRestraintNonCertifiedService();
        $id = $this->params()->fromRoute('id', null);
        $entity = $service->findById($id);
        if ($entity !== null) {
            $documentationId = $this->getDocumentationByType($entity)->getId();
            $service->remove($entity);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The entity was deleted successfully.'), 'success', true);
            return $this->redirectTo('index', array('documentation_id' => $documentationId));
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    protected function storePostData($postData, $restraintNonCertifiedForm, $action = 'add')
    {
        $restraintNonCertifiedForm->setData($postData);
        $isFormValid = $restraintNonCertifiedForm->isValid();
        $entity = $restraintNonCertifiedForm->getObject();

        if ($isFormValid) {
            $this->saveData($entity);
            return $this->redirectToAction($entity, 'index');
        } else {
            return $this->displayForm($restraintNonCertifiedForm, $entity, $action);
        }
    }

    protected function saveData($restraintNonCertified)
    {
        $entity = $this->getRestraintNonCertifiedService()->persist($restraintNonCertified);

        if ($entity) {
            $message = $this->getStandardMessages()->saveSucecssful();
            $this->sendTranslatedFlashMessage($message, "success", true);
        } else {
            $message = $this->getStandardMessages()->saveFailed();
            $this->sendTranslatedFlashMessage($message, "error");
        }
    }

    /**
     * @param LadocRestraintNonCertified $entity
     * @return \Sysco\Aurora\Form\Form
     */
    protected function createAndBindForm($entity)
    {
        /**
         * @var $formFactory RestraintDocumentationFormFactory
         */
        $formFactory = $this->getFormFactory('LadocDocumentation', 'RestraintDocumentation');

        if($this->getType() == "load") {
            if(!($entity->getId() > 0))
                $templateType = (int) $this->params()->fromRoute('template_type', EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD);
            else
                $templateType = $entity->getCarrierDocumentation()->getLowestTaxonomyTemplateType();
        } else {
            $templateType = $entity->getCarrierDocumentation()->getLowestTaxonomyTemplateType();
        }

        $restraintNonCertifiedForm = $formFactory->createRestraintNonCertifiedForm($this->getType(), $templateType);
        $restraintNonCertifiedForm->bind($entity);
        return $restraintNonCertifiedForm;
    }

    protected function displayForm($restraintNonCertifiedForm, $entity = null, $action = 'add')
    {
        if($action == 'add')
            $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        else
            $documentationId = $this->getDocumentationByType($entity)->getId();

        $documentation = $this->getLadocDocumentationService()->findById($documentationId);

        if ($documentation) {
            $templateTypeBreadcrumb = null;
            if ($this->getType() == "load") {
                if ($action == "add")
                    $templateType = (int)$this->params()->fromRoute('template_type', EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD);
                else
                    $templateType = $entity->getCarrierDocumentation()->getLowestTaxonomyTemplateType();

                $templateTypeBreadcrumb = $templateType;
            }

            BreadcrumbCreator::createAddEditBreadcrumbForDocumentationSubPage($this, $documentation, $templateTypeBreadcrumb);

            $viewValues = array(
                'form' => $restraintNonCertifiedForm,
                'documentationId' => $documentationId,
                'type' => $this->getType()
            );

            $view = new ViewModel($viewValues);
            $viewTemplatePath = 'ladoc-documentation/restraint-non-certified/edit.phtml';
            $view->setTemplate($viewTemplatePath);
            return $view;
        }
        else {
            $this->displayGenericErrorMessage();
            $this->redirectToReferer();
        }
    }

    protected function getNewEntity()
    {
        $documentationId = (int) $this->params()->fromRoute('documentation_id', 0);
        $ladocDocumentation = $this->getLadocDocumentationService()->findById($documentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $restraintNonCertified = $this->getRestraintNonCertifiedService()
            ->getNewEntity($ladocDocumentation);

        return $restraintNonCertified;
    }

    protected function redirectToAction($restraintNonCertified, $action)
    {
        if($action == 'add' || $action == 'index') {
            $params = array('documentation_id' => $this->getDocumentationByType($restraintNonCertified)->getId());
            if($this->getType() == "load" && $action == "index")
                $params['template_type'] = $restraintNonCertified->getCarrierDocumentation()->getLowestTaxonomyTemplateType();
            return $this->redirectTo($action, $params);
        } else
            return $this->redirectTo($action, array('id' => $restraintNonCertified->getId()));
    }

    private function getDocumentationByType($restraintNonCertified)
    {
        if($this->getType() == 'load')
            return $restraintNonCertified->getLoadDocumentation();
        else
            return $restraintNonCertified->getCarrierDocumentation();
    }

    protected function redirectTo($action, $params = array())
    {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    protected function getRestraintNonCertifiedService()
    {
        return $this->getService('LadocDocumentation\Service\RestraintNonCertifiedService');
    }

    /**
     * @return LadocDocumentationService
     */
    protected function getLadocDocumentationService()
    {
        return $this->getService('LadocDocumentation\Service\LadocDocumentation');
    }
}