<?php

namespace Certification\Controller;

use Application\Controller\AbstractBaseController;


/**
 * This controller is related to documentation feature
 *  
 */
class CertificationCronController extends AbstractBaseController
{

    public function notifyTimeLimitAction()
    {
       
        $months = $this->params()->fromRoute('months', -1);
        $days = $this->params()->fromRoute('days', -1);
        $this->getCertificationCronService()->notifyUserCertificationBeforeExpire($days, $months);

        return $this->response;
    }

    private function getCertificationCronService()
    {
        return $this->getRegisteredInstance
                        ('Certification\Service\CertificationCronService');
    }

}