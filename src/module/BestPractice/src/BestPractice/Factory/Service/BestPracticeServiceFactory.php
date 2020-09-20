<?php

namespace BestPractice\Factory\Service;

use BestPractice\Service\BestPracticeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BestPracticeServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $imageUtility = $serviceLocator->get('Utility\Image');

        $bestPracticeService = new BestPracticeService(array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'imageUtility' => $imageUtility
            )
        ));

        return $bestPracticeService;
    }

}