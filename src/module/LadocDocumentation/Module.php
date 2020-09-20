<?php

namespace LadocDocumentation;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                //'LadocDocumentation\Service\DocumentationSectionService' => 'LadocDocumentation\Factory\Service\DocumentationSectionServiceFactory',
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'LadocDocumentation\Form\BasicInformationFormFactory' => function ($sm) {
                    $formFactory = new \LadocDocumentation\Form\BasicInformationFormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'LadocDocumentation\Form\PointFormFactory' => function ($sm) {
                    $formFactory = new \LadocDocumentation\Form\PointFormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'LadocDocumentation\Form\WeightAndDimensionsFormFactory' => function ($sm) {
                    $formFactory = new \LadocDocumentation\Form\WeightAndDimensionsFormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'LadocDocumentation\Form\DocumentationAttachmentFormFactory' => function ($sm) {
                    $formFactory = new \LadocDocumentation\Form\DocumentationAttachmentFormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'LadocDocumentation\Form\RestraintDocumentationFormFactory' => function ($sm) {
                    $formFactory = new \LadocDocumentation\Form\RestraintDocumentationFormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'LadocDocumentation\Service\LadocDocumentation' => 'LadocDocumentation\Factory\Service\LadocDocumentationServiceFactory',
                'LadocDocumentation\Service\LoadBasicInformationService' => 'LadocDocumentation\Factory\Service\LoadBasicInformationServiceFactory',
                'LadocDocumentation\Service\CarrierBasicInformationService' => 'LadocDocumentation\Factory\Service\CarrierBasicInformationServiceFactory',
                'LadocDocumentation\Service\LoadLashingPointService' => 'LadocDocumentation\Factory\Service\LoadLashingPointServiceFactory',
                'LadocDocumentation\Service\CarrierLashingPointService' => 'LadocDocumentation\Factory\Service\CarrierLashingPointServiceFactory',
                'LadocDocumentation\Service\LoadLiftingPointService' => 'LadocDocumentation\Factory\Service\LoadLiftingPointServiceFactory',
                'LadocDocumentation\Service\LoadWeightAndDimensionsService' => 'LadocDocumentation\Factory\Service\LoadWeightAndDimensionsServiceFactory',
                'LadocDocumentation\Service\CarrierWeightAndDimensionsService' => 'LadocDocumentation\Factory\Service\CarrierWeightAndDimensionsServiceFactory',
                'LadocDocumentation\Service\LadocDocumentationAttachmentService' => 'LadocDocumentation\Factory\Service\LadocDocumentationAttachmentServiceFactory',
                'LadocDocumentation\Service\CarrierLashingEquipmentService' => 'LadocDocumentation\Factory\Service\CarrierLashingEquipmentServiceFactory',
                'LadocDocumentation\Service\RestraintCertifiedService' => 'LadocDocumentation\Factory\Service\RestraintCertifiedServiceFactory',
                'LadocDocumentation\Service\RestraintCertifiedDocumentService' => 'LadocDocumentation\Factory\Service\RestraintCertifiedDocumentServiceFactory',
                'LadocDocumentation\Service\RestraintNonCertifiedService' => 'LadocDocumentation\Factory\Service\RestraintNonCertifiedServiceFactory'
            ),
        );
    }

}