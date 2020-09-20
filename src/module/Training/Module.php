<?php

namespace Training;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Training\Service\TrainingService' => 'Training\Factory\Service\TrainingServiceFactory',
                'Training\Service\TrainingSectionService' => 'Training\Factory\Service\TrainingSectionServiceFactory',
                'Training\Service\TrainingSectionAttachmentService' => 'Training\Factory\Service\TrainingSectionAttachmentServiceFactory',

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
