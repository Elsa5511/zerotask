<?php

namespace Certification;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'Certification\Form\FormFactory' => function ($sm) {
                    $formFactory = new \Certification\Form\FormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'Certification\Service\CertificationService' => 'Certification\Factory\Service\CertificationServiceFactory',
                'Certification\Service\CertificationCronService' => 'Certification\Factory\Service\CertificationCronServiceFactory',
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
