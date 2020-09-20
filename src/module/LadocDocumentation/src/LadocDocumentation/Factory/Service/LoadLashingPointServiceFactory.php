<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\LoadLashingPointService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class LoadLashingPointServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new LoadLashingPointService(array(
            'entity_manager' => $entityManager
        ));
    }
}