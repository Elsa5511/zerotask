<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/21/15
 * Time: 09:52
 */

namespace Equipment\Factory\Service;

use Equipment\Service\Cache\EquipmentTaxonomyCacheService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentTaxonomyCacheServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentTaxonomyCacheService = new EquipmentTaxonomyCacheService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'cache' => $serviceLocator->get("ZendCacheStorageFactory")
            )
        ));

        return $equipmentTaxonomyCacheService;
    }
}