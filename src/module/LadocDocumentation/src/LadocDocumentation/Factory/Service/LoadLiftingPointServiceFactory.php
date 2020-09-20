<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\LoadLiftingPointService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class LoadLiftingPointServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new LoadLiftingPointService(array(
            'entity_manager' => $entityManager
        ));
    }
}