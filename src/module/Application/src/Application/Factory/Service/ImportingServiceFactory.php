<?php

namespace Application\Factory\Service;

use Application\Service\ImportingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportingServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $imageUtility = $serviceLocator->get('Utility\Image');

        $service = new ImportingService(array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'imageUtility' => $imageUtility,
                'db_ladoc' => $serviceLocator->get('db_ladoc'),
                'db_medoc' => $serviceLocator->get('db_medoc')
            )
        ));
        return $service;
    }

}

?>
