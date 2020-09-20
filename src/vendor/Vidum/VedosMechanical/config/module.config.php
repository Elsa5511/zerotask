<?php

return array(
    'vidum' => array(
        'applications' => array(
            'vedos-mechanical' => array(
                'name' => 'VEDOS MECHANICAL',
                'slug' => 'vedos-mechanical',
                'directory' => 'VedosMechanical',
                'show_category_feature_image' => false,
                'show_equipment_feature_image' => false,
                'home' => array(
                    'application' => 'vedos-mechanical',
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
            'vedos-mechanical' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vedos-mechanical[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'vedos-mechanical',
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