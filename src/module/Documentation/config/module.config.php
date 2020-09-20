<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'doctrine' => array(
        'driver' => array(
            'documentation_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Documentation/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Documentation\Entity' => 'documentation_entities'
                )
            )
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\Documentation' => 'Documentation\Controller\DocumentationController',
            'Controller\DocumentationSection' => 'Documentation\Controller\DocumentationSectionController',
            'Controller\InlineSection' => 'Documentation\Controller\InlineSectionController',
            'Controller\DocumentationSectionAttachment' => 'Documentation\Controller\DocumentationSectionAttachmentController',
            'Controller\InlineSectionAttachment' => 'Documentation\Controller\InlineSectionAttachmentController',
            'Controller\HtmlContentInlineSection' => 'Documentation\Controller\HtmlContentInlineSectionController',
            'Controller\HtmlContentDocumentationSection' => 'Documentation\Controller\HtmlContentDocumentationSectionController',
            'Controller\Page' => 'Documentation\Controller\PageController',
            'Controller\PageSectionAttachment' => 'Documentation\Controller\PageSectionAttachmentController',
            'Controller\PageSection' => 'Documentation\Controller\PageSectionController',
            'Controller\PageInlineSection' => 'Documentation\Controller\PageInlineSectionController',
            'Controller\PageInlineSectionAttachment' => 'Documentation\Controller\PageInlineSectionAttachmentController',
            'Controller\HtmlContentPageInlineSection' => 'Documentation\Controller\HtmlContentPageInlineSectionController',
            'Controller\HtmlContentPageSection' => 'Documentation\Controller\HtmlContentPageSectionController',
            'Controller\CalculatorInfo' => 'Documentation\Controller\CalculatorInfoController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(),
        'blank_template' => 'layout/layout_blank',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Documentation\Entity\BaseHtmlContent' => array(),
                'Documentation\Entity\DocumentationSection' => array(),
                'Documentation\Entity\DocumentationSectionAttachment' => array(),
                'Documentation\Entity\HtmlContentDocumentationSection' => array(),
                'Documentation\Entity\HtmlContentInlineSection' => array(),
                'Documentation\Entity\HtmlContentPageInlineSection' => array(),
                'Documentation\Entity\HtmlContentPageSection' => array(),
                'Documentation\Entity\InlineSection' => array(),
                'Documentation\Entity\InlineSectionAttachment' => array(),
                'Documentation\Entity\Page' => array(),
                'Documentation\Entity\PageInlineSection' => array(),
                'Documentation\Entity\PageInlineSectionAttachment' => array(),
                'Documentation\Entity\PageSection' => array(),
                'Documentation\Entity\PageSectionAttachment' => array(),
                'Documentation\Entity\CalculatorInfo' => array(),
                'Documentation\Entity\CalculatorAttachment' => array()
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'Documentation\Entity\BaseHtmlContent', array('read')),
                    array('user', 'Documentation\Entity\DocumentationSection', array('read')),
                    array('user', 'Documentation\Entity\DocumentationSectionAttachment', array('read')),
                    array('user', 'Documentation\Entity\HtmlContentDocumentationSection', array('read')),
                    array('user', 'Documentation\Entity\HtmlContentInlineSection', array('read')),
                    array('user', 'Documentation\Entity\HtmlContentPageInlineSection', array('read')),
                    array('user', 'Documentation\Entity\HtmlContentPageSection', array('read')),
                    array('user', 'Documentation\Entity\InlineSection', array('read')),
                    array('user', 'Documentation\Entity\InlineSectionAttachment', array('read')),
                    array('user', 'Documentation\Entity\Page', array('read')),
                    array('user', 'Documentation\Entity\PageInlineSection', array('read')),
                    array('user', 'Documentation\Entity\PageInlineSectionAttachment', array('read')),
                    array('user', 'Documentation\Entity\PageSection', array('read')),
                    array('user', 'Documentation\Entity\PageSectionAttachment', array('read')),
                    array('user', 'Documentation\Entity\CalculatorInfo', array('read')),
                    array('user', 'Documentation\Entity\CalculatorAttachment', array('read')),
                    
                    array('admin', 'Documentation\Entity\BaseHtmlContent', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\DocumentationSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\DocumentationSectionAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\HtmlContentDocumentationSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\HtmlContentInlineSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\HtmlContentPageInlineSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\HtmlContentPageSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\InlineSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\InlineSectionAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\Page', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\PageInlineSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\PageInlineSectionAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\PageSection', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\PageSectionAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\CalculatorInfo', array('create', 'update', 'delete')),
                    array('admin', 'Documentation\Entity\CalculatorAttachment', array('create', 'update', 'delete')),
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                ),
            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Controller\Documentation',
                    'action' => array(
                        'index',
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\DocumentationSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\InlineSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\DocumentationSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\DocumentationSectionAttachment',
                    'action' => array(
                        'handle',
                        'video-handle'
                    ),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\InlineSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\InlineSectionAttachment',
                    'action' => array(
                        'handle',
                        'video-handle'
                    ),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\HtmlContentInlineSection',
                    'action' => array(
                        'save'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\HtmlContentDocumentationSection',
                    'action' => array(
                        'save'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Page',
                    'action' => array(
                        'add-page', 'edit-page', 'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Page',
                    'action' => array(
                        'index', 'simple-search'
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\PageSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\PageSectionAttachment',
                    'action' => array(
                        'handle',
                        'video-handle'
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\PageSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\PageInlineSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section'
                    ),
                    'roles' => array('admin')
                ), array(
                    'controller' => 'Controller\PageInlineSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\PageInlineSectionAttachment',
                    'action' => array(
                        'handle',
                        'video-handle'
                    ),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\HtmlContentPageInlineSection',
                    'action' => array(
                        'save'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\HtmlContentPageSection',
                    'action' => array(
                        'save'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CalculatorInfo',
                    'action' => array(
                        'edit'
                    ),
                    'roles' => array('admin')
                ),
            )
        )
    )
);
