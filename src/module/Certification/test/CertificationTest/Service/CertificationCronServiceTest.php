<?php

namespace CertificationTest\Service;

use CertificationTest\BaseSetUp;
use \DateTime;
class CertificationCronServiceTest extends BaseSetUp
{

    public function testSendNotificationBeforeExpired()
    {
        $days = 1;
        $months = 0;
        $expirationDate = new DateTime('TOMORROW');

        $user = new \Application\Entity\User();
        $user->setUserId(2);
        $user->setSuperiorId(null);

        $certification = new \Certification\Entity\Certification();
        $certification->setCertificationId(1);
        $certification->setUser($user);
        $certification->setExpirationDate($expirationDate);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getCertificationBeforeTime')
                ->with($days,$months)
                ->will($this->returnValue($certification));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $this->getCertificationCronService($entityManagerMock,$this->getMailServiceMock())->notifyUserCertificationBeforeExpire($days, $months);
    }

    public function getCertificationCronService($entityManagerMock,$mailServiceMock)
    {
        $certificationCronService = new \Certification\Service\CertificationCronService(array(
            'entity_manager' => $entityManagerMock,
            'dependencies' => array(
                'mail_service' => $mailServiceMock,
            )
        ));
        return $certificationCronService;
    }

    private function getRepositoryMock()
    {
        return $this->getMockBuilder('\Certification\Repository\CertificationRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getMailServiceMock()
    {

        return $this->getMockBuilder('\Application\Service\MailService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getEntityManagerMock($repositoryMock)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}