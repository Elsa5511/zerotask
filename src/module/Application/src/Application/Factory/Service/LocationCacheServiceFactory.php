<?php
namespace Application\Factory\Service;

use Application\Service\Cache\LocationCacheService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationCacheServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentTaxonomyCacheService = new LocationCacheService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'cache' => $serviceLocator->get("ZendCacheStorageFactory")
            )
        ));

        return $equipmentTaxonomyCacheService;
    }
}