<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonCertification for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'doctrine' => array(
        'driver' => array(
            'certification_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Certification/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Certification\Entity' => 'certification_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\Certification' => 'Certification\Controller\CertificationController',
            'Controller\CertificationSection' => 'Certification\Controller\CertificationSectionController',
            'Controller\CertificationSectionAttachment' => 'Certification\Controller\CertificationSectionAttachmentController',
            'Controller\CertificationCron' => 'Certification\Controller\CertificationCronController',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'certification-cron' => array(
                    'options' => array(
                        'route' => 'certification-cron <days> <months>',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Controller',
                            'controller' => 'CertificationCron',
                            'action' => 'notify-time-limit'
                        ),
                    ),
                ),
                'certification-expiration' => array(
                    'options' => array(
                        'route' => 'certification-expiration',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Controller',
                            'controller' => 'Certification',
                            'action' => 'update-after-expire'
                        ),
                    ),
                ),
            )
        )
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
           
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Certification\Entity\Certification' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'Certification\Entity\Certification', array('read')),
                    
                    array('admin', 'Certification\Entity\Certification', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\Certification',
                    'action' => array(
                        'index', 'user'
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\Certification',
                    'action' => array(
                        'add', 'edit', 'delete', 'delete-many', 'report', 'update-after-expire', 'export-report'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CertificationSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CertificationSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CertificationSectionAttachment',
                    'action' => array(
                        'handle'
                    ),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\CertificationCron',
                    'action' => array(
                        'notify-time-limit',
                    ),
                    'roles' => array('user', 'admin')
                ),
            )
        )
    ),
);
