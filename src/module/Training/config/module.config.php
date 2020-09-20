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
            'training_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Training/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Training\Entity' => 'training_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\Training' => 'Training\Controller\TrainingController',
            'Controller\TrainingSection' => 'Training\Controller\TrainingSectionController',
            'Controller\TrainingSectionAttachment'=>'Training\Controller\TrainingSectionAttachmentController',
       
        ),
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
                'Training\Entity\TrainingSection' => array(),
                'Training\Entity\TrainingSectionAttachment' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'Training\Entity\TrainingSection', array('read')),
                    array('user', 'Training\Entity\TrainingSectionAttachment', array('read')),
                    
                    array('admin', 'Training\Entity\TrainingSection', array('create', 'update', 'delete')),
                    array('admin', 'Training\Entity\TrainingSectionAttachment', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\Training',
                    'action' => array(
                        'index',
                    ),
                    'roles' => array('admin','user')
                ),
                array(
                    'controller' => 'Controller\TrainingSection',
                    'action' => array(
                        'add-section',
                        'edit-section',
                        'delete-section',
                       
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\TrainingSectionAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\TrainingSectionAttachment',
                    'action' => array(
                        'handle'
                    ),
                    'roles' => array('user','admin')
                ),
            )
        )
    ),
);
