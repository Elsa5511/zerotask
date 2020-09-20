<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class IndexController extends AbstractBaseController
{

    public function indexAction()
    {
        $applications = $this->getApplicationService()->getApplications();
        
        if(count($applications) === 1){
            $application = current($applications);
            return $this->redirect()->toRoute('base', array('application' => $application->getSlug()));
        }

        return new ViewModel(array(
            'applications' => $applications
                )
        );
    }
    
    protected function getApplicationService(){
        return $this->getService('Application\Service\ApplicationService');
    }

}
