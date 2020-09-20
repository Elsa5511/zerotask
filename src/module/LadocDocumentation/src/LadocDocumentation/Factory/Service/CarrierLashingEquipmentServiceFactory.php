<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\CarrierLashingEquipmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class CarrierLashingEquipmentServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new CarrierLashingEquipmentService(array(
            'entity_manager' => $entityManager
        ));
    }
}