<?php

return array(
    'vidum' => array(
        'applications' => array(
            'vedos-medical' => array(
                'name' => 'VEDOS MEDICAL',
                'slug' => 'vedos-medical',
                'directory' => 'VedosMedical',
                'show_category_feature_image' => false,
                'show_equipment_feature_image' => false,
                'home' => array(
                    'application' => 'vedos-medical',
                    'controller' => 'equipment',
                    'action' => 'index'
                ),
                'features' => array(
                    'instances',
                    'attachments',
                  
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'vedos-medical' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vedos-medical[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'vedos-medical',
                        '__NAMESPACE__' => 'Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                    'constraints' => array(
                        'application' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]+',
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'wildcard',
                    ),
                ),
            ),
        )
    )
);