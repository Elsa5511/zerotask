<?php

namespace Equipment\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Equipment\Service\EquipmentInstanceControlDateService;

class EquipmentInstanceControlDateServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $equipmentInstanceControlDateService = new EquipmentInstanceControlDateService(
                array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));

        return $equipmentInstanceControlDateService;
    }

}