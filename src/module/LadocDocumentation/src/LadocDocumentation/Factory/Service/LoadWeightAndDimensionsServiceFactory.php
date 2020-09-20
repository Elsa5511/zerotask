<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\LoadWeightAndDimensionsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoadWeightAndDimensionsServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new LoadWeightAndDimensionsService(array(
            'entity_manager' => $entityManager
        ));
    }
}
