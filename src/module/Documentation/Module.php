<?php

namespace Documentation;

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
                'Documentation\Service\DocumentationSectionService' => 'Documentation\Factory\Service\DocumentationSectionServiceFactory',
                'Documentation\Service\InlineSectionService' => 'Documentation\Factory\Service\InlineSectionServiceFactory',
                'Documentation\Service\DocumentationSectionAttachmentService' => 'Documentation\Factory\Service\DocumentationSectionAttachmentServiceFactory',
                'Documentation\Service\InlineSectionAttachmentService' => 'Documentation\Factory\Service\InlineSectionAttachmentServiceFactory',
                'Documentation\Service\HtmlContentInlineSectionService' => 'Documentation\Factory\Service\HtmlContentInlineSectionServiceFactory',
                'Documentation\Service\HtmlContentDocumentationSectionService' => 'Documentation\Factory\Service\HtmlContentDocumentationSectionServiceFactory',
                'Documentation\Service\PageService' => 'Documentation\Factory\Service\PageServiceFactory',
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'Documentation\Form\FormFactory' => function ($sm) {
                    $formFactory = new \Documentation\Form\FormFactory();
                    $formFactory->setTranslator($sm->get('translator'));
                    $formFactory->setObjectManager($sm->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'Documentation\Service\PageSectionService' => 'Documentation\Factory\Service\PageSectionServiceFactory',
                'Documentation\Service\PageSectionAttachmentService' => 'Documentation\Factory\Service\PageSectionAttachmentServiceFactory',
                'Documentation\Service\PageInlineSectionService' => 'Documentation\Factory\Service\PageInlineSectionServiceFactory',
                'Documentation\Service\PageInlineSectionAttachmentService' => 'Documentation\Factory\Service\PageInlineSectionAttachmentServiceFactory',
                'Documentation\Service\HtmlContentPageInlineSectionService' => 'Documentation\Factory\Service\HtmlContentPageInlineSectionServiceFactory',
                'Documentation\Service\HtmlContentPageSectionService' => 'Documentation\Factory\Service\HtmlContentPageSectionServiceFactory',
                'Documentation\Service\CalculatorInfoService' => 'Documentation\Factory\Service\CalculatorInfoServiceFactory'
            ),
        );
    }

}