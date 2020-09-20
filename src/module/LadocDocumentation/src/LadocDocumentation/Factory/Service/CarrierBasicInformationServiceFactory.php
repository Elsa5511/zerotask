<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\CarrierBasicInformationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CarrierBasicInformationServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new CarrierBasicInformationService(array(
            'entity_manager' => $entityManager
        ));
    }
}
