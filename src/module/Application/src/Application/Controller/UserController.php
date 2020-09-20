<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Form\User\FieldDisplayOptions as UserFieldDisplayOptions;
use Application\Service\UserService;

class UserController extends AbstractBaseController {

    const ADMIN_ROLE_ID = 'admin';
    const USER_ROLE_ID = 'user';

    /**
     * List of Users
     */
    public function indexAction() {
        $users = $this->getUserService()->fetchAll();

        return new ViewModel(array(
            'users' => $users,
            'title' => 'Admin: ' . $this->getTranslator()->translate('Users'),
        ));
    }

    /**
     * Form to add a new user
     * 
     */
    public function addAction() {
        $translator = $this->getTranslator();
        $formFactory = $this->getFormFactory();
        $form = $formFactory->createUserFormForNewUser($this->isVedosApplication());
        $request = $this->getRequest();
        $application = $this->params()->fromRoute('application');
        $cancelRedirect = "/$application/user";
        $currentUser = $this->getUserService()->getUser($this->getCurrenUser()->getUserId());

        $user = new \Application\Entity\User();
        $form->bind($user);

        if ($request->isPost()) {
            $formData = $request->getPost()->get('user');
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $this->getRoleService()->setUserRole($user, $formData['role-id']);
                $id = $this->getUserService()->persistFormData($user, $currentUser);
                if ($id > 0) {
                    $this->sendTranslatedFlashMessage($this->getTranslator()->translate('User has been successfully saved.'));
                } else {
                    $this->sendTranslatedFlashMessage($this->getTranslator()->translate('User could not be saved at this time.'), 'Error');
                }
                return $this->redirectTo('index');
            }
        }
        else {
            $fieldsetUser = $form->get(UserController::USER_ROLE_ID);
            $fieldsetUser->get('role-id')->setValue('user');
        }

        return array(
            'form' => $form,
            'title' => $translator->translate('Add user'),
            'cancelRedirect' => $cancelRedirect
        );
    }

    /**
     *
     *
     * @return array
     */
    public function editAction() {
        $userService = $this->getUserService();
        $application = $this->params()->fromRoute('application');
        $userId = (int) $this->getEvent()->getRouteMatch()->getParam('id');
        $user = $userService->getUser($userId);
        if (empty($user)) {
            return $this->redirectTo('add');
        }

        // Account settings action
        $isUsersOwnAccount = $this->isEditingOwnAccount();
        if ($isUsersOwnAccount) {
            $title = $this->getTranslator()->translate('Edit account');
            $cancelRedirect = "/";
        } else {
            $title = $this->getTranslator()->translate('Edit user');
            $cancelRedirect = "/$application/user";
        }

        $roleEntity = $user->getRoles();
        $userHasRole = isset($roleEntity) && (count($roleEntity) > 0);
        $roleId = ($userHasRole) ? $roleEntity[0]->getRoleId() : 0;
        $userPassword = $user->getPassword();
        $currentUserIsAdmin = $this->currentUserIsAdmin();
        $currentEncryptedPassword = $this->getCurrenUser()->getPassword();
        $userFieldDisplayOptions = new UserFieldDisplayOptions($isNewUser = false, $currentUserIsAdmin, $isUsersOwnAccount, $this->isVedosApplication());
        $form = $this->getFormFactory()->createUserForm($userFieldDisplayOptions, $currentEncryptedPassword);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $formData = $request->getPost()->get('user');
            $form->setData($request->getPost());

            if ($form->isValid()) {
                if ((!$isUsersOwnAccount) && ($roleId != $formData['role-id'])) {
                    $user = $this->getRoleService()->setNewRoleForUser(
                            $user, $roleId, $formData['role-id']);
                }

                $user->setPassword($userPassword);
                $id = $userService->persistFormData($form->getObject(), $user, $formData);
                if ($id > 0) {
                    $this->sendTranslatedFlashMessage($this->getTranslator()->translate('User has been successfully saved.'));
                } else {
                    $this->sendTranslatedFlashMessage($this->getTranslator()->translate('User could not be saved at this time.'), 'Error');
                }

                if ($application) {
                    if ($currentUserIsAdmin && !$isUsersOwnAccount)
                        return $this->redirectTo('index');
                    else
                        return $this->redirectToPath('equipment', 'index');
                } else {
                    return $this->redirect()->toRoute('home');
                }
            }
        } else {
            $form->bind($user);
            if ($currentUserIsAdmin) {
                $fieldsetUser = $form->get('user');
                $fieldsetUser->get('role-id')->setValue($roleId);
            }
        }

        $values = array(
            'title' => $title,
            'form' => $form,
            'cancelRedirect' => $cancelRedirect
        );

        $view = new ViewModel($values);
        $view->setTemplate('application/user/add.phtml');
        return $view;
    }

    private function currentUserIsAdmin() {
//        $userService = $this->getUserService();
//        $currentUser = $userService->getUser($this->getCurrenUser()->getUserId());
//        $roleEntity = $currentUser->getRoles();
//        $userHasRole = isset($roleEntity) && (count($roleEntity) > 0);
//        $roleId = ($userHasRole) ? $roleEntity[0]->getRoleId() : 0;
//        return $roleId === self::ADMIN_ROLE_ID;
        $currentUser = $this->getCurrenUser();
        return  $currentUser->hasRole('admin');
    }
    
    private function isEditingOwnAccount() {
        $currentUserId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $editingUserId = $this->getEvent()->getRouteMatch()->getParam('id');
        return ($currentUserId == $editingUserId);
    }

    /*
     * Edit your own account
     * 
     */

    public function accountAction() {
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        $this->getEvent()->getRouteMatch()->setParam('id', $userId);
        $this->getEvent()->getRouteMatch()->setParam('isAccount', true);
        return $this->editAction();
    }

//    private function getNewUserForm() {
//        return $this->getUserForm();
//    }
//
//    private function getEditUserForm($userId, $isAccount) {
//        return $this->getUserForm($userId, $isAccount, false);
//    }

//    private function getUserForm($userId = 0, $isAccount = false, $isNewUser = true) {
//        $formFactory = $this->getFormFactory();
//        $form = $formFactory->createUserForm($userId, $isAccount, $isNewUser, $this->getCurrenUser()->getPassword());
//        return $form;
//    }

    /**
     * This method is used when a user forgot its password
     * 
     * @return array
     */
    public function forgotPasswordAction() {
        $title = $this->getTranslator()->translate('Reset password');
        $form = $this->getFormFactory()->createForgotPasswordForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $user = $form->getData();
                $this->getUserService()->sendForgotPasswordEmail($user);
                $flashMessage = $this->getTranslator()
                        ->translate('We sent you an email. Please check your inbox ');
                $this->flashMessenger()->setNamespace('success')->addMessage($flashMessage);
                return false;
            }
        }
        return array("form" => $form, 'title' => $title);
    }

    /**
     * This method is used when user received an email and click on link containing
     * an encrypted variable to corroborate user really want to reset password.
     * The generated link is only enabled for 30 minutes.
     *
     * return array
     */
    public function resetPasswordAction() { // TODO: Refactor service to use service messages.
        $securityKey = $this->params()->fromRoute('key', null);
        if (isset($securityKey)) {
            $wasReset = $this->getUserService()->resetPassword($securityKey);

            if ($wasReset) {
                $nameSpace = 'success';
                $message = $this->getTranslator()->translate(
                        'We sent you a new password. Please check your email.');
            } else {
                $nameSpace = 'error';
                $message = $this->getTranslator()->translate(
                        'The time to reset your password has expired. Please try again.');
            }
            $this->flashMessenger()->setNamespace($nameSpace)->addMessage($message);
        }
    }

    /**
     * Deactivate a user
     */
    public function deactivateAction() {
        return $this->changeUserState(true);
    }
    
    /**
     * Reactivate a user
     */
    public function reactivateAction() {
        return $this->changeUserState(false);
    }
    
    private function changeUserState($deactivate = true) {
        $userId = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        $config = $this->serviceLocator->get('Config');
        $passwordExpiration = $config['vidum']['password_expiration'];

        // result 1 = updated, 0 = nothing happened
        $result = $this->getUserService()->changeState($userId, 
                $deactivate ? UserService::USER_STATE_DELETED : UserService::USER_STATE_ACTIVE,
                $passwordExpiration['enabled']);
        $message = ($result > 0) ? 
                ($deactivate ? $this->getTranslator()->translate('User deactivated successfully.') : 
                    $this->getTranslator()->translate('User reactivated successfully.'))
                : null;
        $this->flashMessenger()->setNamespace('success')->addMessage($message);
        return $this->redirectTo('index');
    }

    /**
     * Delete many users
     * Call by ajax 
     */
    public function deleteManyAction() {
        $post = $this->getRequest()->getPost();

        // array list of user ids, who will be deleted
        $list = $post->delete_list;

        // result 1 = updated, 0 = nothing happened
        $result = $this->getUserService()->deleteMany($list);
        $successMessage = ($result > 0) ? $this->getTranslator()->translate('"x" users deleted successfully.') : null;
        $message = str_replace('"x"', $result, $successMessage);
        $this->flashMessenger()->setNamespace('success')->addMessage($message);

        return $this->redirectTo('index');
    }

    public function passwordExpirationAction() {
        $config = $this->serviceLocator->get('Config');
        $passwordExpiration = $config['vidum']['password_expiration'];

        if($passwordExpiration['enabled']) {
            $expiresIn = $passwordExpiration['expire_in'];
            $warnBefore = $passwordExpiration['warn_before'];

            $this->getUserService()->checkPasswordExpiration($config['hostname_console'], $expiresIn, $warnBefore);
        }

        return $this->response;
    }

    /**
     * Check if
     * @return bool
     */
    private function isVedosApplication() {
        $application = $this->params()->fromRoute('application');
        $showOrganizationRestriction = false;
        if($application == "vedos-medical" || $application == 'vedos-mechanical')
            $showOrganizationRestriction = true;

        return $showOrganizationRestriction;
    }

    private function redirectTo($action) {
        return $this->redirect()->toRoute('base/wildcard', array(
                    'application' => $this->params()->fromRoute('application'),
                    'controller' => 'user',
                    'action' => $action,
        ));
    }

    private function getRoleService() {
        return $this->getServiceLocator()->get(
                        'Application\Service\RoleService');
    }

    /**
     * @return \Application\Service\UserService
     */
    private function getUserService() {
        return $this->getServiceLocator()->get(
                        'Application\Service\UserService');
    }

}
