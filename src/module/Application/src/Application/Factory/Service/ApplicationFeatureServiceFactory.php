<?php

namespace Application\Factory\Service;

use Application\Service\ApplicationFeatureService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationFeatureServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Vidum\Config');

        $applicationService = new ApplicationFeatureService(array(
            'applications' => $config['applications'],
            'features' => $config['features']
        ));

        return $applicationService;
    }

}