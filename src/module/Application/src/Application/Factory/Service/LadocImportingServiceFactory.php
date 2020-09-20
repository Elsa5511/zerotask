<?php

namespace Application\Factory\Service;

use Application\Service\LadocImportingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LadocImportingServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $imageUtility = $serviceLocator->get('Utility\Image');

        $service = new LadocImportingService(array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'imageUtility' => $imageUtility,
                'db_ladoc' => $serviceLocator->get('db_ladoc')
            )
        ));
        return $service;
    }

}

?>
