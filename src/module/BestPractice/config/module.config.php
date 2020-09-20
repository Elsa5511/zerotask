<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonTraining for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'doctrine' => array(
        'driver' => array(
            'best_practice_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/BestPractice/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'BestPractice\Entity' => 'best_practice_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\BestPractice' => 'BestPractice\Controller\BestPracticeController',
            'Controller\BestPracticeAttachment' => 'BestPractice\Controller\BestPracticeAttachmentController',
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'notification-newrevision' => array(
                    'options' => array(
                        'route' => 'notification-newrevision',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Controller',
                            'controller' => 'BestPractice',
                            'action' => 'newRevisionNotifications'
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
                'BestPractice\Entity\AttachmentTaxonomy' => array(),
                'BestPractice\Entity\BestPractice' => array(),
                'BestPractice\Entity\BestPracticeAttachment' => array(),
                'BestPractice\Entity\Subscription' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'BestPractice\Entity\AttachmentTaxonomy', array('read')),
                    array('user', 'BestPractice\Entity\BestPractice', array('read')),
                    array('user', 'BestPractice\Entity\BestPracticeAttachment', array('read')),
                    array('user', 'BestPractice\Entity\Subscription', array('read')),
                    
                    array('admin', 'BestPractice\Entity\AttachmentTaxonomy', array('create', 'update', 'delete')),
                    array('admin', 'BestPractice\Entity\BestPractice', array('create', 'update', 'delete')),
                    array('admin', 'BestPractice\Entity\BestPracticeAttachment', array('create', 'update', 'delete')),
                    array('admin', 'BestPractice\Entity\Subscription', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\BestPractice',
                    'action' => array(
                        'index', 'detail', 'subscribe', 'unsubscribe', 'export-to-pdf',
                        'procedures', 'user-manual', 'additional-info'
                    ),
                    'roles' => array('admin','user')
                ),
                array(
                    'controller' => 'Controller\BestPractice',
                    'action' => array(
                        'add', 'edit', 'delete', 'newRevisionNotifications',
                        'revision-history', 'old-revision-detail',
                        'procedures-old-revision', 'user-manual-old-revision',
                        'additional-info-old-revision'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\BestPracticeAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\BestPracticeAttachment',
                    'action' => array(
                        'handle',
                    ),
                    'roles' => array('user', 'admin')
                ),
            )
        )
    ),
);
