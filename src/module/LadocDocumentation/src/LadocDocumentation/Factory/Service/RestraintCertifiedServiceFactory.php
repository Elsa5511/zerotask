<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\RestraintCertifiedService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class RestraintCertifiedServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new RestraintCertifiedService(array(
            'entity_manager' => $entityManager
        ));
    }
}