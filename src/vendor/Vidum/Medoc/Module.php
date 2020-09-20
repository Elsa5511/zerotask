<?php

namespace Vidum\Medoc;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements DependencyIndicatorInterface
{

    protected $moduleDependencies = array('Equipment', 'Training', 'Documentation', 'Certification', 'Quiz');

    public function getModuleDependencies()
    {
        return $this->moduleDependencies;
    }

    public function onBootstrap(MvcEvent $e)
    {
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
