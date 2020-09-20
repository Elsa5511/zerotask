<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class ApplicationController extends AbstractBaseController {

    public function indexAction() {
        if(!$this->getCurrenUser()) {
            /* For guest access - se acl.local.php.dist */
            $config = $this->getServiceLocator()->get('Config');
            if(isset($config['guest_access_applications'])) {
                $arrayOfApplicationsThatUserCanAccess = $config['guest_access_applications'];
            } else {
                $arrayOfApplicationsThatUserCanAccess = array();
            }
        } else {
            $currentUserId = $this->getCurrenUser()->getUserId();
            $currentUser = $this->getUserService()->getUser($currentUserId);
            $applicationsThatUserCanAccess = $currentUser->getAccessibleApplications();
            $namesOfApplicationsThatUserCanAccess = $applicationsThatUserCanAccess->map(function($element) {
                return $element->getName();
            });
            $arrayOfApplicationsThatUserCanAccess = $namesOfApplicationsThatUserCanAccess->toArray();
        }

        $applications = $this->getApplicationService()->getApplications($arrayOfApplicationsThatUserCanAccess);

        if (count($applications) === 1) {
            $application = current($applications);
            return $this->redirect()->toRoute('base/wildcard', $application->getHome());
        }

        return new ViewModel(array(
            'applications' => $applications
                )
        );
    }

    protected function getApplicationService() {
        return $this->getService('Application\Service\ApplicationService');
    }

    public function translatableAction() {
        return $this->getApplicationService()->vidumTranslatable();
    }

    protected function getUserService() {
        return $this->getService('Application\Service\UserService');
    }

}
