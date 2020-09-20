<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\CarrierWeightAndDimensionsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CarrierWeightAndDimensionsServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new CarrierWeightAndDimensionsService(array(
            'entity_manager' => $entityManager
        ));
    }

}