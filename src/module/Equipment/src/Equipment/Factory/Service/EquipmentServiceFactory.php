<?php

namespace Equipment\Factory\Service;

use Equipment\Service\EquipmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentService = new EquipmentService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));

        return $equipmentService;
    }

}