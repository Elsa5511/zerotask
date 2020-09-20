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
            'ladoc_documentation_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/LadocDocumentation/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'LadocDocumentation\Entity' => 'ladoc_documentation_entities'
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
            'Controller\LadocDocumentation' => 'LadocDocumentation\Controller\LadocDocumentationController',
            'Controller\LoadBasicInformation' => 'LadocDocumentation\Controller\LoadBasicInformationController',
            'Controller\CarrierBasicInformation' => 'LadocDocumentation\Controller\CarrierBasicInformationController',
            'Controller\LoadLashingPoint' => 'LadocDocumentation\Controller\LoadLashingPointController',
            'Controller\CarrierLashingPoint' => 'LadocDocumentation\Controller\CarrierLashingPointController',
            'Controller\LoadLiftingPoint' => 'LadocDocumentation\Controller\LoadLiftingPointController',
            'Controller\BasicInformation' => 'LadocDocumentation\Controller\BasicInformationController',
            'Controller\LoadWeightAndDimensions' => 'LadocDocumentation\Controller\LoadWeightAndDimensionsController',
            'Controller\CarrierWeightAndDimensions' => 'LadocDocumentation\Controller\CarrierWeightAndDimensionsController',
            'Controller\LadocDocumentationAttachment' => 'LadocDocumentation\Controller\LadocDocumentationAttachmentController',
            'Controller\LadocRestraintCertifiedDocument' => 'LadocDocumentation\Controller\LadocRestraintCertifiedDocumentController',
            'Controller\CarrierLashingEquipment' => 'LadocDocumentation\Controller\CarrierLashingEquipmentController',
            'Controller\LoadRestraintCertified' => 'LadocDocumentation\Controller\LoadRestraintCertifiedController',
            'Controller\LoadRestraintNonCertified' => 'LadocDocumentation\Controller\LoadRestraintNonCertifiedController',
            'Controller\CarrierRestraintCertified' => 'LadocDocumentation\Controller\CarrierRestraintCertifiedController',
            'Controller\CarrierRestraintNonCertified' => 'LadocDocumentation\Controller\CarrierRestraintNonCertifiedController'
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
                'LadocDocumentation\Entity\LadocDocumentation' => array(),
                'LadocDocumentation\Entity\LoadBasicInformation' => array(),
                'LadocDocumentation\Entity\CarrierBasicInformation' => array(),
                'LadocDocumentation\Entity\FormOfTransportation' => array(),
                'LadocDocumentation\Entity\ResponsibleOffice' => array(),
                'LadocDocumentation\Entity\Stanag' => array(),
                'LadocDocumentation\Entity\LoadLashingPoint' => array(),
                'LadocDocumentation\Entity\LoadLashingPointAttachment' => array(),
                'LadocDocumentation\Entity\CarrierLashingPoint' => array(),
                'LadocDocumentation\Entity\CarrierLashingPointAttachment' => array(),
                'LadocDocumentation\Entity\LoadLiftingPoint' => array(),
                'LadocDocumentation\Entity\LoadLiftingPointAttachment' => array(),
                'LadocDocumentation\Entity\LoadWeightAndDimensions' => array(),
                'LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment' => array(),
                'LadocDocumentation\Entity\CarrierWeightAndDimensions' => array(),
                'LadocDocumentation\Entity\CarrierWeight' => array(),
                'LadocDocumentation\Entity\CarrierWeightAttachment' => array(),
                'LadocDocumentation\Entity\CarrierDimensions' => array(),
                'LadocDocumentation\Entity\CarrierDimensionsAttachment' => array(),
                'LadocDocumentation\Entity\LadocDocumentationAttachment' => array(),
                'LadocDocumentation\Entity\LadocDocumentationDescription' => array(),
                'LadocDocumentation\Entity\CarrierLashingEquipment' => array(),
                'LadocDocumentation\Entity\CarrierLashingEquipmentAttachment' => array(),
                'LadocDocumentation\Entity\LadocRestraintCertified' => array(),
                'LadocDocumentation\Entity\LadocRestraintCertifiedAttachment' => array(),
                'LadocDocumentation\Entity\LadocRestraintCertifiedDocument' => array(),
                'LadocDocumentation\Entity\LadocRestraintNonCertified' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'LadocDocumentation\Entity\LadocDocumentation', array('read')),
                    array('admin', 'LadocDocumentation\Entity\LadocDocumentation', array('create', 'update', 'delete')),

                    array('user', 'LadocDocumentation\Entity\LoadBasicInformation', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierBasicInformation', array('read')),
                    array('user', 'LadocDocumentation\Entity\FormOfTransportation', array('read')),
                    array('user', 'LadocDocumentation\Entity\ResponsibleOffice', array('read')),
                    array('user', 'LadocDocumentation\Entity\Stanag', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadLashingPoint', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadLashingPointAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierLashingPoint', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierLashingPointAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadLiftingPoint', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadLiftingPointAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadWeightAndDimensions', array('read')),
                    array('user', 'LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierWeightAndDimensions', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierWeight', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierDimensions', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierWeightAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierDimensionsAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocDocumentationAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocDocumentationDescription', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierLashingEquipment', array('read')),
                    array('user', 'LadocDocumentation\Entity\CarrierLashingEquipmentAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocRestraintCertified', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocRestraintCertifiedAttachment', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocRestraintCertifiedDocument', array('read')),
                    array('user', 'LadocDocumentation\Entity\LadocRestraintNonCertified', array('read')),

                    array('admin', 'LadocDocumentation\Entity\LoadBasicInformation', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierBasicInformation', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadLashingPoint', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadLashingPointAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierLashingPoint', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierLashingPointAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadLiftingPoint', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadLiftingPointAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadWeightAndDimensions', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierWeightAndDimensions', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierWeight', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierDimensions', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierWeightAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierDimensionsAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocDocumentationAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocDocumentationDescription', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierLashingEquipment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\CarrierLashingEquipmentAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocRestraintCertified', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocRestraintCertifiedAttachment', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocRestraintCertifiedDocument', array('create', 'update', 'delete')),
                    array('admin', 'LadocDocumentation\Entity\LadocRestraintNonCertified', array('create', 'update', 'delete')),
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
                    'controller' => 'Controller\LadocDocumentation',
                    'action' => array(
                        'index',
                        'display',
                        'create',
                        'wizard',
                        'description'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LadocDocumentation',
                    'action' => array(
                        'index',
                        'display'
                    ),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\LoadBasicInformation',
                    'action' => array(
                        'add',
                        'edit',
                        'edit-wizard',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierBasicInformation',
                    'action' => array(
                        'add',
                        'edit',
                        'edit-wizard',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadLashingPoint',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierLashingPoint',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadLiftingPoint',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadWeightAndDimensions',
                    'action' => array(
                        'add',
                        'edit',
                        'edit-wizard'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierWeightAndDimensions',
                    'action' => array(
                        'add',
                        'edit',
                        'edit-wizard',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LadocDocumentationAttachment',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierLashingEquipment',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadRestraintCertified',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete',
                        'detail'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadRestraintCertified',
                    'action' => array(
                        'detail'
                    ),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\LoadRestraintNonCertified',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete',
                        'detail'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LoadRestraintNonCertified',
                    'action' => array(
                        'detail'
                    ),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\CarrierRestraintCertified',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete',
                        'detail'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierRestraintCertified',
                    'action' => array(
                        'detail'
                    ),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\LadocRestraintCertifiedDocument',
                    'action' => array(
                        'index',
                        'add',
                        'edit',
                        'delete',
                        'detail'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\LadocRestraintCertifiedDocument',
                    'action' => array(
                        'detail'
                    ),
                    'roles' => array('user')
                ),
                array(
                    'controller' => 'Controller\CarrierRestraintNonCertified',
                    'action' => array(
                        'add',
                        'edit',
                        'index',
                        'delete',
                        'detail'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\CarrierRestraintNonCertified',
                    'action' => array(
                        'detail'
                    ),
                    'roles' => array('user')
                ),
            )
        )
    )
);
