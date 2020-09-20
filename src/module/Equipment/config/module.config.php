<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonEquipment for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'doctrine' => array(
        'driver' => array(
            'equipment_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Equipment/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Equipment\Entity' => 'equipment_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\Equipment' => 'Equipment\Controller\EquipmentController',
            'Controller\VedosMechanicalEquipment' => 'Equipment\Controller\VedosMechanicalEquipmentController',
            'Controller\EquipmentTaxonomy' => 'Equipment\Controller\EquipmentTaxonomyController',
            'Controller\EquipmentInstance' => 'Equipment\Controller\EquipmentInstanceController',
            'Controller\EquipmentInstanceReport' => 'Equipment\Controller\EquipmentInstanceReportController',
            'Controller\EquipmentInstanceContainer' => 'Equipment\Controller\EquipmentInstanceContainerController',
            'Controller\EquipmentInstanceAttachment' => 'Equipment\Controller\EquipmentInstanceAttachmentController',
            'Controller\EquipmentAttachment' => 'Equipment\Controller\EquipmentAttachmentController',
            'Controller\PeriodicControl' => 'Equipment\Controller\PeriodicControlController',
            'Controller\EquipmentSetup' => 'Equipment\Controller\EquipmentSetupController',
            'Controller\PeriodicControlAttachment' => 'Equipment\Controller\PeriodicControlAttachmentController',
            'Controller\CheckInAndOut' => 'Equipment\Controller\CheckInAndOutController',
            'Controller\VisualControl' => 'Equipment\Controller\VisualControlController',
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
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Equipment\Entity\Checkin' => array(),
                'Equipment\Entity\Checkout' => array(),
                'Equipment\Entity\CompetenceAreaTaxonomy' => array(),
                'Equipment\Entity\ControlPoint' => array(),
                'Equipment\Entity\ControlPointOption' => array(),
                'Equipment\Entity\ControlPointResult' => array(),
                'Equipment\Entity\ControlTemplate' => array(),
                'Equipment\Entity\ControlPointToTemplate' => array(),
                'Equipment\Entity\Equipment' => array(),
                'Equipment\Entity\VedosMechanicalEquipment' => array(),
                'Equipment\Entity\EquipmentAttachment' => array(),
                'Equipment\Entity\EquipmentInstance' => array(),
                'Equipment\Entity\EquipmentInstanceContainer' => array(),
                'Equipment\Entity\EquipmentInstanceAttachment' => array(),
                'Equipment\Entity\EquipmentInstanceTaxonomy' => array(),
                'Equipment\Entity\Equipmentmeta' => array(),
                'Equipment\Entity\EquipmentTaxonomy' => array(),
                'Equipment\Entity\PeriodicControl' => array(),
                'Equipment\Entity\PeriodicControlAttachment' => array(),
                'Equipment\Entity\PeriodicControlTaxonomy' => array(),
                'Equipment\Entity\VisualControl' => array(),
                'Equipment\Entity\EquipmentInstanceHistorical' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'Equipment\Entity\Checkin', array('read')),
                    array('user', 'Equipment\Entity\Checkout', array('read')),
                    array('user', 'Equipment\Entity\CompetenceAreaTaxonomy', array('read')),
                    array('user', 'Equipment\Entity\ControlPoint', array('read')),
                    array('user', 'Equipment\Entity\ControlPointOption', array('read')),
                    array('user', 'Equipment\Entity\ControlPointResult', array('read')),
                    array('user', 'Equipment\Entity\ControlTemplate', array('read')),
                    array('user', 'Equipment\Entity\ControlPointToTemplate', array('read')),
                    array('user', 'Equipment\Entity\Equipment', array('read')),
                    array('user', 'Equipment\Entity\VedosMechanicalEquipment', array('read')),
                    array('user', 'Equipment\Entity\EquipmentAttachment', array('read')),
                    array('user', 'Equipment\Entity\EquipmentInstance', array('read')),
                    array('user', 'Equipment\Entity\EquipmentInstanceContainer', array('read')),
                    array('user', 'Equipment\Entity\EquipmentInstanceAttachment', array('read')),
                    array('user', 'Equipment\Entity\EquipmentInstanceTaxonomy', array('read')),
                    array('user', 'Equipment\Entity\Equipmentmeta', array('read')),
                    array('user', 'Equipment\Entity\EquipmentTaxonomy', array('read')),
                    array('user', 'Equipment\Entity\PeriodicControlAttachment', array('read')),
                    array('user', 'Equipment\Entity\PeriodicControlTaxonomy', array('read')),
                    array('user', 'Equipment\Entity\VisualControl', array('read')),
                    array('user', 'Equipment\Entity\PeriodicControl', array('read')),

                    array(array('admin', 'warehouse_worker', 'controller'), 'Equipment\Entity\Checkin', array('create', 'update', 'delete')),
                    array(array('admin', 'warehouse_worker', 'controller'), 'Equipment\Entity\Checkout', array('create', 'update', 'delete')),
                    array('admin', 'Equipment\Entity\CompetenceAreaTaxonomy', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\ControlPoint', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\ControlPointOption', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\ControlPointResult', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\ControlTemplate', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\ControlPointToTemplate', array('create', 'update', 'delete')),
                    array(array('admin'), 'Equipment\Entity\Equipment', array('create', 'update', 'delete')),
                    array(array('admin'), 'Equipment\Entity\VedosMechanicalEquipment', array('create', 'update', 'delete')),
                    array(array('admin'), 'Equipment\Entity\EquipmentAttachment', array('create', 'update', 'delete')),
                    array(array('admin', 'controller', 'warehouse_worker'),
                        'Equipment\Entity\EquipmentInstance', array('create', 'update')),
                    array(array('admin', 'controller', 'warehouse_worker'),
                        'Equipment\Entity\EquipmentInstanceContainer', array('create', 'update')),
                    array(array('admin'), 'Equipment\Entity\EquipmentInstance', array('delete', 'deactivate')),
                    array(array('admin'), 'Equipment\Entity\EquipmentInstanceContainer', array('delete', 'deactivate')),
                    array(array('admin', 'controller'), 'Equipment\Entity\EquipmentInstanceHistorical', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\EquipmentInstanceAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Equipment\Entity\EquipmentInstanceTaxonomy', array('create', 'update', 'delete')),
                    array(array('admin'), 'Equipment\Entity\Equipmentmeta', array('create', 'update', 'delete')),
                    array('admin', 'Equipment\Entity\EquipmentTaxonomy', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\PeriodicControlAttachment', array('create', 'update', 'delete')),
                    array('admin', 'Equipment\Entity\PeriodicControlTaxonomy', array('create', 'update', 'delete')),
                    array(array('admin', 'warehouse_worker', 'controller'), 'Equipment\Entity\VisualControl', array('create', 'update', 'delete')),
                    array(array('admin', 'controller'), 'Equipment\Entity\PeriodicControl', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\Equipment',
                    'action' => array('index', 'detail', 'attachment-index', 'do-search', 'simple-search', 'search'),
                    'roles' => array('user', 'admin', 'controller')
                ),

                array(
                    'controller' => 'Controller\VedosMechanicalEquipment',
                    'action' => array('index', 'detail', 'attachment-index', 'do-search', 'simple-search', 'search'),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\Equipment',
                    'action' => array('admin-index', 'edit', 'add', 'delete', 'delete-many',
                        'add-equipment-attachment',
                        'edit-equipment-attachment',
                        'delete-equipment-attachment',
                        'deactivate',
                        'reactivate',
                        'deactivate-many'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\VedosMechanicalEquipment',
                    'action' => array('admin-index', 'edit', 'add', 'delete', 'delete-many',
                        'add-equipment-attachment',
                        'edit-equipment-attachment',
                        'delete-equipment-attachment',
                        'deactivate',
                        'reactivate',
                        'deactivate-many'),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentTaxonomy',
                    'action' => array('index'),
                    'roles' => array('user', 'admin')
                ),
                array(
                    'controller' => 'Controller\EquipmentTaxonomy',
                    'action' => array(
                        'add',
                        'edit',
                        'delete',
                        'admin-index',
                        'deactivate',
                        'reactivate',
                        'deactivate-many'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstance',
                    'action' => array('index', 'detail', 'view-last-periodic-control',
                        'view-last-visual-control', 'equipment-instance', 'unlink',
                        'do-search', 'do-control-search', 'simple-search', 'export-search', 'export-control-search'),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceContainer',
                    'action' => array('index', 'detail', 'view-last-periodic-control',
                        'view-last-visual-control', 'equipment-instance', 'unlink', 'do-search', 'simple-search'),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstance',
                    'action' => array('add', 'edit', 'copy', 'add-subinstance', 'deactivate-many',
                        'update-many', 'edit-many',
                        'export-periodic-control-report', 'export-expired-guarantee-report',
                        'export-expired-lifetime-report'
                    ),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstance',
                    'action' => array('deactivate', 'activate'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceReport',
                    'action' => array(
                        'expired-periodic-control',
                        'expired-periodic-control-for-equipment',
                        'expired-periodic-control-for-category',
                        'expired-guarantee-for-category',
                        'expired-guarantee-for-equipment',
                        'expired-guarantee',
                        'expired-lifetime-for-category',
                        'expired-lifetime-for-equipment',
                        'expired-lifetime',
                    ),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceContainer',
                    'action' => array('add', 'edit', 'copy', 'add-subinstance', 'deactivate-many',
                        'update-many', 'edit-many', 'report-periodic-control',
                        'report-expired-guarantee', 'report-expired-lifetime',
                        'export-periodic-control-report', 'export-expired-guarantee-report',
                        'export-expired-lifetime-report'
                    ),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceContainer',
                    'action' => array('deactivate', 'activate'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment',
                        'delete-many-attachment',
                    ),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentInstanceAttachment',
                    'action' => array(
                        'handle', 'video-handle'
                    ),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\EquipmentAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment',
                        'delete-many-attachment'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\EquipmentAttachment',
                    'action' => array(
                        'handle', 'video-handle'
                    ),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\PeriodicControlAttachment',
                    'action' => array(
                        'add-attachment',
                        'edit-attachment',
                        'delete-attachment',
                        'delete-many-attachment'
                    ),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\PeriodicControlAttachment',
                    'action' => array(
                        'handle','video-handle'
                    ),
                    'roles' => array('user', 'admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\Attachment',
                    'action' => array('add-attachment', 'edit-attachment', 'delete-attachment'),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\PeriodicControl',
                    'action' => array('index', 'add', 'save', 'delete', 'export-to-pdf'),
                    'roles' => array('admin', 'controller')
                ),
                array(
                    'controller' => 'Controller\PeriodicControl',
                    'action' => array('index', 'export-to-pdf'),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\CheckInAndOut',
                    'action' => array('checkout', 'checkin'),
                    'roles' => array('admin', 'warehouse_worker', 'controller')
                ),
                array(
                    'controller' => 'Controller\CheckInAndOut',
                    'action' => array('detail-checkout'),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\VisualControl',
                    'action' => array('index', 'add', 'delete', 'save'),
                    'roles' => array('admin', 'warehouse_worker', 'controller')
                ),
                array(
                    'controller' => 'Controller\VisualControl',
                    'action' => array('index'),
                    'roles' => array('user')
                ),
            )
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'setup-equipment-data' => array(
                    'options' => array(
                        'route' => 'equipment setup',
                        'defaults' => array(
                            'controller' => 'Controller\EquipmentSetup',
                            'action' => 'setup-equipment-data'
                        )
                    )
                )
            )
        )
    )
);
