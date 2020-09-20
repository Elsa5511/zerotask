<?php

namespace Certification\Factory\Service;

use Certification\Service\CertificationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CertificationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $certificationService = new CertificationService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'mail_service' => $serviceLocator->get('Application\Service\MailService'),
            )
        ));

        return $certificationService;
    }

}