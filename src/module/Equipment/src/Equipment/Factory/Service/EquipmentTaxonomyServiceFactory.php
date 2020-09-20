<?php

namespace Equipment\Factory\Service;

use Equipment\Service\EquipmentTaxonomyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentTaxonomyServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $equipmentService = new EquipmentTaxonomyService(array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));

        return $equipmentService;
    }

}