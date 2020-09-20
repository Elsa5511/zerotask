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
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
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
            'Controller\Application' => 'Application\Controller\ApplicationController',
            'Controller\Index' => 'Application\Controller\IndexController',
            'Controller\Language' => 'Application\Controller\LanguageController',
            'Controller\User' => 'Application\Controller\UserController',
            'Controller\Attachment' => 'Application\Controller\AttachmentController',
            'Controller\Organization' => 'Application\Controller\OrganizationController',
            'Controller\Location' => 'Application\Controller\LocationController',
            'Controller\Role' => 'Application\Controller\RoleController',
            'Controller\LoadSecurity' => 'Application\Controller\LoadSecurityController',
            'Controller\LoadSecurityAttachment' => 'Application\Controller\LoadSecurityAttachmentController',
            'Controller\Importing' => 'Application\Controller\ImportingController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/403' => __DIR__ . '/../view/error/403.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Application\Entity\Role' => array(),
                'Application\Entity\User' => array(),
                'Application\Entity\Language' => array(),
                'Application\Entity\Organization' => array(),
                'Application\Entity\Attachment' => array(),
                'Application\Entity\AttachmentTaxonomy' => array(),
                'Application\Entity\BaseTaxonomy' => array(),
                'Application\Entity\Country' => array(),
                'Application\Entity\LocationTaxonomy' => array(),
                'Application\Entity\ReportTable' => array(),
                'Application\Entity\Section' => array(),
                'Application\Entity\ApplicationDescription' => array(),
                'Application\Entity\Feature' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('guest', 'Application\Entity\User', array('read', 'update')),
                    array('guest', 'Application\Entity\ApplicationDescription', array('read')),
                    array('guest', 'Application\Entity\Feature', array('read')),

                    array('user', 'Application\Entity\ApplicationDescription', array('read')),
                    array('user', 'Application\Entity\User', array('read', 'update')),
                    array('user', 'Application\Entity\Role', array('read')),
                    array('user', 'Application\Entity\Language', array('read')),
                    array('user', 'Application\Entity\Organization', array('read')),
                    array('user', 'Application\Entity\Attachment', array('read')),
                    array('user', 'Application\Entity\AttachmentTaxonomy', array('read')),
                    array('user', 'Application\Entity\BaseTaxonomy', array('read')),
                    array('user', 'Application\Entity\Country', array('read')),
                    array('user', 'Application\Entity\LocationTaxonomy', array('read')),
                    array('user', 'Application\Entity\ReportTable', array('read')),
                    array('user', 'Application\Entity\Section', array('read')),
                    array('user', 'Application\Entity\Feature', array('read')),
                    
                    array('admin', 'Application\Entity\ApplicationDescription', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\User', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Role', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Language', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Organization', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Attachment', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\AttachmentTaxonomy', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\BaseTaxonomy', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Country', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\LocationTaxonomy', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\ReportTable', array('create', 'update', 'delete')),
                    array('admin', 'Application\Entity\Section', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\Index',
                    'action' => array('index'),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\Application',
                    'action' => array('index'),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\User',
                    'action' => array('index', 'deactivate', 'reactivate', 'edit', 'add', 'delete-many', 'password-expiration'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Organization',
                    'action' => array('deactivate', 'activate', 'edit', 'add', 'deactivate-many'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Organization',
                    'action' => array('index'),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\Location',
                    'action' => array('search'),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\Location',
                    'action' => array('delete', 'edit', 'add', 'delete-many'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Location',
                    'action' => array('index'),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\User',
                    'action' => array('account'),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\Role',
                    'action' => array('index', 'add', 'edit', 'delete'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Language',
                    'action' => array('index', 'edit', 'delete', 'add'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\User',
                    'action' => array('forgot-password', 'reset-password'),
                    'roles' => array('guest')
                ),
                array(
                    'controller' => 'Controller\Attachment',
                    'action' => array('index', 'handle','video-handle'),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\Attachment',
                    'action' => array('add', 'edit', 'delete'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadSecurity',
                    'action' => array('index'),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\LoadSecurityAttachment',
                    'action' => array('index', 'add', 'edit', 'delete'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Importing',
                    'action' => array('index', 'ladoc-import', 'restore', 'backup'),
                    'roles' => array('admin')
                )
            )
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'setup-translatable' => array(
                    'options' => array(
                        'route' => 'vidum translatable',
                        'defaults' => array(
                            'controller' => 'Controller\Application',
                            'action' => 'translatable'
                        )
                    )
                ),
                'import-content' => array(
                    'options' => array(
                        'route' => 'import content',
                        'defaults' => array(
                            'controller' => 'Controller\Importing',
                            'action' => 'index'
                        )
                    )
                ),
                'ladoc-import-content' => array(
                    'options' => array(
                        'route' => 'ladoc-import content',
                        'defaults' => array(
                            'controller' => 'Controller\Importing',
                            'action' => 'ladoc-import'
                        )
                    )
                ),
                'password-expiration' => array(
                    'options' => array(
                        'route' => 'password expiration',
                        'defaults' => array(
                            'controller' => 'Controller\User',
                            'action' => 'password-expiration'
                        )
                    )
                )
            )
        )
    )
);
