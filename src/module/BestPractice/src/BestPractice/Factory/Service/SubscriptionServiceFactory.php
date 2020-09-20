<?php

namespace BestPractice\Factory\Service;

use BestPractice\Service\SubscriptionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SubscriptionServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $subscriptionService = new SubscriptionService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
            )
        ));

        return $subscriptionService;
    }

}