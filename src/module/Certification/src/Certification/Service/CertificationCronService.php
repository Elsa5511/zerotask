<?php

namespace Certification\Service;

use Application\Service\AbstractBaseService;

class CertificationCronService extends AbstractBaseService
{

    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('Certification\Entity\Certification');
    }

    protected function getEquipmentRepository()
    {
        return $this->getEntityManager()->getRepository('Equipment\Entity\Equipment');
    }

    public function notifyUserCertificationBeforeExpire($days, $months)
    {

        $certifications = $this->getEntityRepository()->getCertificationBeforeTime($days, $months);
       
        if (!empty($certifications)) {
            foreach ($certifications as $certification) {

                $this->sendNotificationBeforeExpired($certification, $days, $months);
                
            }
        }
    }

    private function sendNotificationBeforeExpired($certification, $days, $months)
    {

        $superior = $certification->getUser()->getSuperiorId();
        $subject = "Vidum - System | " . $this->translate("Certification notification");

        $templateParams = $this->setTemplateParams($certification, $days, $months);

        $userEmail = $certification->getUser()->getEmail();
        $this->getDependency('mail_service')->sendMessage($userEmail, $subject, $templateParams);
        $hasSuperior = !empty($superior);

        if ($hasSuperior) {
            $this->sendToSuperior($certification, $templateParams, $subject, $superior);
        }
    }

    private function sendToSuperior($certification, $templateParams, $subject, $superior)
    {
        $superiorEmail = $superior->getEmail();
        $templateParams['params']['userFullname'] = $superior;
        $templateParams = array_merge_recursive($templateParams, array(
            'params' => array(
                'dependent' => $certification->getUser()->getDisplayName()
            )
                )
        );
        $this->getDependency('mail_service')->sendMessage($superiorEmail, $subject, $templateParams);
    }

    private function setTemplateParams($certification, $days, $months)
    {
        $userFullname = $certification->getUser()->getDisplayName();
        $userId = $certification->getUser()->getUserId();
        $equipmentName = $certification->getEquipment()->getTitle();

        $params = array(
            'equipmentName' => $equipmentName,
            'userFullname' => $userFullname,
            'userId' => $userId,
            'months' => $months,
            'days' => $days,
            
        );

        $templateParams = array(
            'source' => 'template/notification/notification-warning-expired',
            'params' => $params,
            'map' => $this->templateMap
        );
        return $templateParams;
    }

}