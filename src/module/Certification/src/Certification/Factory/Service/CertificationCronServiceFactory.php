<?php

namespace Certification\Factory\Service;

use Certification\Service\CertificationCronService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CertificationCronServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $certificationCronService = new CertificationCronService(array(
            'entity_manager' => $entityManager,
            'templateMap' => array(
                'template/notification/notification-warning-expired' => __DIR__ . '/../../../../view/template/notification/notification-warning-expired.phtml',
            ),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'mail_service' => $serviceLocator->get('Application\Service\MailService'),
            )
        ));

        return $certificationCronService;
    }

}