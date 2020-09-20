<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;

class RoleController extends AbstractBaseController {

    /**
     * Show table with a role list
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction() {
        $roles = $this->getRoleService()->getAllButGuest();

        return new ViewModel(array(
            'roles' => $roles,
        ));
    }

    /**
     * Add a new role
     */
    public function addAction() {
        $roleService = $this->getRoleService();

        $role = $roleService->getNewRole();
        $form = $this->getRoleForm($role);

        $request = $this->getRequest();
        if ($request->isPost()) {

            $postData = $request->getPost();
            $roleId = $postData['role']['role_id'];

            $isRoleUnique = $roleService->isRoleUnique($roleId);
            if (!$isRoleUnique) {
                $this->sendFlashMessage('Role already exists', 'error');
                return $this->redirectToPath('role');
            }

            $form->setData($postData);

            $result = $this->sendPostedDataToService($form, $role, $roleId);

            if ($result instanceOf \Zend\Http\Response) {
                return $result;
            }
        }

        return array(
            'form' => $form
        );
    }

    /* Edit an existing role */

    public function editAction() {
        $roleId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        if (!$roleId) {
            $this->sendFlashMessage('Undefined parameter role_id.', 'error');
            return $this->redirectToPath('role');
        }

        $role = $this->getRoleService()->findById($roleId);
        if (!$role) {
            $this->displayGenericErrorMessage();
            return $this->redirectToPath('role');
        }

        $form = $this->getRoleForm($role);
        $form->get('role')->get('role_id')->setValue($roleId);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            $roleId = $postData['role']['role_id'];

            $form->setData($postData);

            $result = $this->sendPostedDataToService($form, $role, $roleId);

            if ($result instanceOf \Zend\Http\Response) {
                return $result;
            }
        }

        return new ViewModel(array(
            'roleId' => $roleId,
            'form' => $form
        ));
    }

    private function sendPostedDataToService(&$form, &$role, $roleId) {
        if ($form->isValid()) {
            $role->setRoleId($roleId);
            $this->getRoleService()->persist($role);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Role has been successfully saved.'));


            return $this->redirectToPath('role');
        } else {
            $form->get('role')->get('role_id')->setValue($roleId);
        }

        $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Role could not be saved at this time.'), 'error');
    }

    /**
     *  Delete an existing role
     */
    public function deleteAction() {
        $roleService = $this->getRoleService();
        $roleId = $this->params()->fromRoute('id');
        $role = $roleService->findById($roleId);

        if ($role) {
            if (!$this->isCurrentUserRole($roleId)) {
                $flashMessengerArray = $roleService->deleteById($role); // TODO: Refactor service to send servicemessage
                $this->sendFlashMessage($flashMessengerArray['message'], $flashMessengerArray['namespace'], true);
            }
        } else {
            $message = sprintf($this->getTranslator()->translate("Could not find %s with id %u"), $this->getTranslator()->translate('role'), $roleId);
            $this->sendTranslatedFlashMessage($message, 'error');
        }

        return $this->redirectToPath('role');
    }

    private function isCurrentUserRole($roleId) {
        $isCurrentRole = false;
        $currentUserRoles = $this->getCurrenUser()->getRoles();
        if (count($currentUserRoles) > 0) {
            $userRoleId = $currentUserRoles[0]->getRoleId();
            $isCurrentRole = $userRoleId === $roleId;
        }
        if ($isCurrentRole) {
            $errorMessage = sprintf($this->getTranslator()->translate(
                            "You cannot delete role \"%s\" because it is your current user role. Edit your role and try again."), $roleId);
            $this->sendFlashMessage($errorMessage, "error", true);
        }
        return $isCurrentRole;
    }

    private function getRoleService() {
        return $this->getServiceLocator()
                        ->get('Application\Service\RoleService');
    }

    private function getRoleForm(\Application\Entity\Role $role) {
        $formFactory = $this->getFormFactory();
        $form = $formFactory->createRoleForm($role->getRoleId());
        $form->bind($role);
        return $form;
    }

}
