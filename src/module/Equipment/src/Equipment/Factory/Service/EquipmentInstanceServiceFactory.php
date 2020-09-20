<?php

namespace Equipment\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Equipment\Service\EquipmentInstanceService;

class EquipmentInstanceServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentInstanceService = new EquipmentInstanceService(
                array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));

        return $equipmentInstanceService;
    }

}