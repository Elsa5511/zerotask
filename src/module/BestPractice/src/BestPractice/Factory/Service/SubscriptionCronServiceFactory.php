<?php

namespace BestPractice\Factory\Service;

use BestPractice\Service\SubscriptionCronService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SubscriptionCronServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $subscriptionCronService = new SubscriptionCronService(array(
            'entity_manager' => $entityManager,
            'templateMap' => array(
                'template/notification/notification-new-revision' => __DIR__ . '/../../../../view/template/notification/notification-new-revision.phtml',
            ),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator'),
                'mail_service' => $serviceLocator->get('Application\Service\MailService'),
            )
        ));

        return $subscriptionCronService;
    }

}