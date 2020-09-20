<?php

namespace BestPractice;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'BestPractice\Form\FormFactory' => function ($sm) {
                    $formFactory = new \BestPractice\Form\FormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'BestPractice\Service\BestPracticeService' => 'BestPractice\Factory\Service\BestPracticeServiceFactory',
                'BestPractice\Service\SubscriptionService' => 'BestPractice\Factory\Service\SubscriptionServiceFactory',
                'BestPractice\Service\SubscriptionCronService' => 'BestPractice\Factory\Service\SubscriptionCronServiceFactory',
                'BestPractice\Service\BestPracticeAttachmentService' => 'BestPractice\Factory\Service\BestPracticeAttachmentServiceFactory',
                'BestPractice\Service\BestPracticeExporter' => function() {
                    return new Service\BestPracticeExporter();
                }
        ));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            )
        );
    }

}
