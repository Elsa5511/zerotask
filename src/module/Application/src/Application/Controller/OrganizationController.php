<?php

namespace Application\Controller;

use Application\Controller\Helper\DeactivationHelper;
use Application\Service\OrganizationService;
use Zend\View\Model\ViewModel;

class OrganizationController extends AbstractBaseController {

    /**
     * List of Organizations
     *
     */
    public function indexAction() {
        $organizations = $this->getOrganizationService()->fetchAll();
        $title = $this->getAdminTitle() . $this->translate('Organizations');
        return new ViewModel(array(
            'organizations' => $organizations,
            'title' => $title
        ));
    }

    /**
     * Add Organization Form
     *
     * @return ViewModel $view
     */
    public function addAction() {
        $organization = $this->getNewOrganization();
        $organizationForm = $this->getOrganizationForm($organization);

        $request = $this->getRequest();
        if ($request->isPost()) {
            return $this->savePostData($request->getPost(), $organizationForm);
        }
        else {
            return $this->displayForm($organizationForm);
        }
    }

    /**
     * Display organization form
     *
     * @param type $organizationForm
     * @return ViewModel a view
     */
    private function displayForm($organizationForm, $organizationId = 0) {
        $viewValues = array(
            'organizationId' => $organizationId,
            'form' => $organizationForm
        );
        if ($organizationId == 0) {
            $view = new ViewModel($viewValues);
            $view->setTemplate('application/organization/edit.phtml');
            return $view;
        }
        else {
            return $viewValues;
        }
    }

    /**
     * Edit Organization Action
     *
     */
    public function editAction() {
        $organizationId = $this->getEvent()
            ->getRouteMatch()->getParam('id', false);
        $organization = $this->getOrganization($organizationId);
        if ($organization !== null) {
            $organizationForm = $this->getOrganizationForm($organization);
            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->savePostData($request->getPost(), $organizationForm);
            }
            else {
                return $this->displayForm($organizationForm, $organizationId);
            }
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectTo('index');
        }
    }

    /**
     * Validate the post data, then store it
     * or return a validation message
     *
     * @param type $post
     * @param type $organizationForm
     * @return redirects or display the form
     */
    private function savePostData($post, $organizationForm) {
        $organizationForm->setData($post);
        $organization = $organizationForm->getObject();
        $service = $this->getOrganizationService();


        if ($organizationForm->isValid()) {
            $nameIsUnique = $service->nameIsUnique($organization->getName(),
                $organization->getOrganizationId());

            if (!$nameIsUnique) {
                $this->sendTranslatedFlashMessage($this->translate('Organization name is already in use.'), 'error');
            }
            else {
                $this->saveOrganization($organization);
                return $this->redirectTo('index');
            }

        }
        return $this->displayForm($organizationForm, $organization->getOrganizationId());
    }

    /**
     * Get a organization entity object
     *
     * @param type $id
     * @return type
     */
    private function getOrganization($id) {
        $organization = $this->getOrganizationService()->getOrganization($id);
        return $organization;
    }

    /**
     * Get a new organization entity object
     *
     * @return type
     */
    private function getNewOrganization() {
        $organization = new \Application\Entity\Organization();
        return $organization;
    }

    private function saveOrganization($organization) {
        $resultId = $this->getOrganizationService()->persistData($organization);
        if ($resultId > 0) {
            $this->sendTranslatedFlashMessage($this->translate('Organization has been successfully saved.'));
        }
        else {
            $this->sendTranslatedFlashMessage($this->translate('Organization could not be saved at this time.', 'error'));
        }
    }

    public function deactivateAction() {
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateAction($this->params()->fromRoute('id', 0));
        return $this->redirectTo('index');
    }

    public function activateAction() {
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->activateAction($this->params()->fromRoute('id', 0));
        return $this->redirectTo('index');
    }

    public function deactivateManyAction() {
        $post = $this->getRequest()->getPost();
        $ids = explode(',', $post->deactivate_ids);

        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateManyAction($ids);
        return $this->redirectTo('index');
    }

    private function getOrganizationForm($organization) {
        $formFactory = $this->getFormFactory();
        $form = $formFactory->createOrganizationForm();
        $form->bind($organization);
        return $form;
    }

    private function redirectTo($action) {
        return $this->redirectToPath('organization', $action);
    }

    /**
     * @return OrganizationService
     */
    private function getOrganizationService() {
        return $this->getService('Application\Service\OrganizationService');
    }

    private function getDeactivationHelper() {
        return new DeactivationHelper($this, $this->getOrganizationService(), $this->getTranslator());
    }

}
