<?php

namespace Acl;

use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Events;
use Zend\Console\Console;
use Zend\Mvc\ModuleRouteListener;

class Module {

    public function onBootstrap(MvcEvent $mvcEvent) {
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        if (!Console::isConsole()) { // && array_key_exists('environment', $GLOBALS) && $GLOBALS['environment'] !== 'testing') {
            $this->setupEntitiesAuthorization($mvcEvent);
        }

        $this->setupDefaultEntityRepositoryFQCN($mvcEvent);
    }

    public function setupEntitiesAuthorization(MvcEvent $mvcEvent, $application = null) {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();


        if ($serviceManager->has('BjyAuthorize\Service\Authorize')) {
            $authorize = $serviceManager->get('BjyAuthorize\Service\Authorize');

            $options = array();

            if (null !== $application) {
                $options['application'] = $application;
            }

            $listener = new \Acl\Listener\Listener($options, $authorize);

            $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

            $entityManager->getEventManager()->addEventListener(array(
                Events::postLoad,
                Events::preUpdate,
                Events::prePersist,
                Events::preRemove
                    ), $listener);

            $mvcEvent->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) use ($listener) {
                $application = $e->getRouteMatch()->getParam('application', null);
                $listener->setOptions(array('application' => $application));
            });
        }
    }

    private function setupDefaultEntityRepositoryFQCN(MvcEvent $mvcEvent) {
        $serviceManager = $mvcEvent->getApplication()->getServiceManager();

        $fqcn = 'Acl\Repository\EntityRepository';

        $config = $serviceManager->get('doctrine.configuration.orm_default');
        $config->setDefaultRepositoryClassName($fqcn);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
