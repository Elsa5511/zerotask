<?php

namespace Application\Factory\Service;

use Application\Service\LocationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $service = new LocationService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));
        return $service;
    }
}
