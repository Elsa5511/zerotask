<?php
namespace Equipment;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Equipment\Service\EquipmentService' => 'Equipment\Factory\Service\EquipmentServiceFactory',
                'Equipment\Service\EquipmentmetaService' => function($sm) {
                    $serviceEquipmentmeta = new \Equipment\Service\EquipmentmetaService($sm);
                    return $serviceEquipmentmeta;
                },
                'Equipment\Service\EquipmentTaxonomyService' => 'Equipment\Factory\Service\EquipmentTaxonomyServiceFactory',
                'Equipment\Service\EquipmentInstanceService' => 'Equipment\Factory\Service\EquipmentInstanceServiceFactory',
                'Equipment\Service\EquipmentInstanceContainerService' => 'Equipment\Factory\Service\EquipmentInstanceContainerServiceFactory',
                'Equipment\Service\PeriodicControlService' => 'Equipment\Factory\Service\PeriodicControlServiceFactory',
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'Equipment\Form\FormFactory' => function ($sm) {
                    $formFactory = new \Equipment\Form\FormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'Equipment\Service\EquipmentAttachmentService' => 'Equipment\Factory\Service\EquipmentAttachmentServiceFactory',
                'Equipment\Service\EquipmentInstanceAttachmentService' => 'Equipment\Factory\Service\EquipmentInstanceAttachmentServiceFactory',
                'Equipment\Service\PeriodicControlAttachmentService' => 'Equipment\Factory\Service\PeriodicControlAttachmentServiceFactory',
                'Equipment\Service\CheckoutService' => 'Equipment\Factory\Service\CheckoutServiceFactory',
                'Equipment\Service\CheckinService' => 'Equipment\Factory\Service\CheckinServiceFactory',
                'Equipment\Service\VisualControlService' => 'Equipment\Factory\Service\VisualControlServiceFactory',
                'Equipment\Service\PeriodicControlReportService' => function($serviceManager) {
                    return new Service\PeriodicControlReportService($serviceManager->get('translator'));
                },
                'Equipment\Service\EquipmentInstanceReportService' => function($serviceManager) {
                    return new Service\EquipmentInstanceReportService($serviceManager->get('translator'));
                },
                'Equipment\Service\EquipmentInstanceControlDateService' => 'Equipment\Factory\Service\EquipmentInstanceControlDateServiceFactory',
                'Equipment\Service\Cache\EquipmentTaxonomyCacheService' => 'Equipment\Factory\Service\EquipmentTaxonomyCacheServiceFactory',
                'ZendCacheStorageFactory' => function() {
                    return \Zend\Cache\StorageFactory::factory(
                        array(
                            'adapter' => array(
                                'name' => 'filesystem',
                                'options' => array(
                                    'dirLevel' => 2,
                                    'cacheDir' => './data/cache',
                                    'dirPermission' => 0755,
                                    'filePermission' => 0666,
                                    'namespaceSeparator' => '-db-',
                                    'ttl' => 86400 //24 hours
                                ),
                            ),
                            'plugins' => array('serializer'),
                        )
                    );
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
