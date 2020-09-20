<?php

namespace Application\Factory\Service;

use Application\Service\ApplicationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');        
        $config = $serviceLocator->get('Vidum\Config');

        $applicationService = new ApplicationService(array(
            'entity_manager' => $entityManager,
            'applications' => $config['applications'] ,
            'translatable' => $config['translatable'] ,
        ));

        return $applicationService;
    }

}