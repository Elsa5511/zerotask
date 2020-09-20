<?php

namespace Certification\Service;

use Application\Service\AbstractBaseService;
use Certification\Entity\Certification;

class CertificationService extends AbstractBaseService
{

    protected function getEntityRepository()
    {
        return $this->getRepository('Certification\Entity\Certification');
    }

    protected function getEquipmentRepository()
    {
        return $this->getRepository('Equipment\Entity\Equipment');
    }

    public function getNewCertification($equipmentId)
    {
        $equipment = $this->getEquipmentRepository()->find($equipmentId);
        if ($equipment) {
            $certification = new Certification();
            $certification->setEquipment($equipment);
        } else {
            $certification = null;
        }

        return $certification;
    }

    public function findByEquipment($equipmentId)
    {
        return $this->getEntityRepository()->findBy(
                        array("equipment" => $equipmentId)
        );
    }

    public function findByUser($userId)
    {
        return $this->getEntityRepository()->findBy(
                        array("user" => $userId, "application" => $this->application)
        );
    }

    public function persistData($certification)
    {
        $today = new \DateTime("now");
        $isPassed = $certification->getTheoryPassed() && $certification->getPracticalPassed();
        $isNotExpired = $certification->getExpirationDate() > $today;        
        $isValid = $isPassed && $isNotExpired;
        $certification->setValid($isValid);

        parent::persist($certification);

        $this->sendNotificationChange($certification);

        return $certification->getCertificationId();
    }

    private function sendNotificationChange($certification)
    {
        $certifiedUser = $certification->getUser();
        
        if($certifiedUser) {
            $subject = "Vidum - System | " . $this->translate("Certification notification");

            $templateParams = $this->setTemplateParams($certification);

            $userEmail = $certifiedUser->getEmail();
            if($userEmail)
                $this->getDependency('mail_service')->sendMessage($userEmail, $subject, $templateParams);
            
            $superior = $certifiedUser->getSuperiorId();
            $hasSuperior = !empty($superior);
            if ($hasSuperior) {
                $this->sendToSuperior($certification, $templateParams, $subject, $superior);
            }
        }
    }

    private function sendToSuperior($certification, $templateParams, $subject, $superior)
    {
        $superiorEmail = $superior->getEmail();
        if($superiorEmail) {
            $templateParams['params']['userFullname'] = $superior;
            $templateParams = array_merge_recursive($templateParams, array(
                    'params' => array(
                        'dependent' => $certification->getUser()->getDisplayName()
                    )
                )
            );
            $this->getDependency('mail_service')->sendMessage($superiorEmail, $subject, $templateParams);
        }
    }

    private function setTemplateParams($certification)
    {
        $userFullname = $certification->getUser()->getDisplayName();
        $userId = $certification->getUser()->getUserId();
        $equipmentName = $certification->getEquipment()->getTitle();

        $params = array(
            'equipmentName' => $equipmentName,
            'userFullname' => $userFullname,
            'userId' => $userId,
        );
        $templateParams = array(
            'source' => 'template/notification/notification-update',
            'params' => $params,
        );
        return $templateParams;
    }

    /**
     * 
     * @param $certificationId
     * @return array Flash message
     */
    public function deleteById($certificationId)
    {
        $certification = $this->getEntityRepository()->find($certificationId);

        if ($certification) {
            $this->remove($certification);
            $namespace = "success";
            $messageFormat = $this->translate("\"%s\" certification for \"%s\" was deleted successfully.");
            $message = sprintf($messageFormat, $certification->getEquipment(), $certification->getUser());
        } else {
            $namespace = "error";
            $message = $this->translate("Certification doesn\'t exist");
        }

        return array(
            "namespace" => $namespace,
            "message" => $message
        );
    }

    public function deleteByIds($certificationIds)
    {
        $flashMessengerArray = array();
        foreach ($certificationIds as $certificationId) {
            $flashMessengerArray[] = $this->deleteById($certificationId);
        }
        return $flashMessengerArray;
    }

    public function search($postData)
    {
        if (array_key_exists('equipment', $postData)) {
            $equipmentList = $postData['equipment'];
            unset($postData['equipment']);
        } else {
            $equipmentList = null;
        }

        $certifications = $this->getEntityRepository()
                ->getCertificationBySearch($equipmentList, $postData);
        return $certifications;
    }

    public function updateCertificationAfterExpire()
    {
        $certifications = $this->getEntityRepository()->getByPassedExpirationTime();
        if (!empty($certifications)) {
            foreach ($certifications as $certification) {
                $certification->setValid(false);
                $this->getEntityManager()->persist($certification);
            }
            $this->getEntityManager()->flush();
        }
    }

    public function createReportTable($certifications) {
        $headerValues = $this->createReportHeaderValues();
        $dataTable = array();
        foreach ($certifications as $certification) {
            $hasTheoryExamYesOrNo = $certification->getTheoryPassed() ? 'Yes' : 'No';
            $hasPracticalExamYesOrNo = $certification->getPracticalPassed() ? 'Yes' : 'No';
            $isCertifiedYesOrNo = $certification->isValid() ? 'Yes' : 'No';
            $dataRow = array(
                $certification->getEquipment()->getTitle(),
                $certification->getUser()->getDisplayName(),
                $this->translate($hasTheoryExamYesOrNo),
                $this->translate($hasPracticalExamYesOrNo),
                $this->translate($isCertifiedYesOrNo),
                $certification->getExpirationDate()
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Certifications report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }
    
    private function createReportHeaderValues() {
        return array(
            $this->translate('Equipment'),
            $this->translate('User'),
            $this->translate('Theory exam'),
            $this->translate('Practical exam'),
            $this->translate('Certified'),
            $this->translate('Expires')
        );
    }
}