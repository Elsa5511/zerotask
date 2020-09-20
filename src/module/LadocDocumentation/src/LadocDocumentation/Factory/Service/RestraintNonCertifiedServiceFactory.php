<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\RestraintNonCertifiedService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RestraintNonCertifiedServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new RestraintNonCertifiedService(array(
            'entity_manager' => $entityManager
        ));
    }
}