<?php

namespace Equipment\Factory\Service;


use Equipment\Service\EquipmentInstanceContainerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentInstanceContainerServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentInstanceService = new EquipmentInstanceContainerService(
            array(
                'entity_manager' => $entityManager,
                'dependencies' => array(
                    'translator' => $serviceLocator->get('translator')
                )
            ));

        return $equipmentInstanceService;
    }
}