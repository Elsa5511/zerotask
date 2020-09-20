<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\CarrierLashingPointService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class CarrierLashingPointServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new CarrierLashingPointService(array(
            'entity_manager' => $entityManager
        ));
    }
}