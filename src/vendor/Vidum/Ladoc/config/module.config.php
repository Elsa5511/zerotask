<?php

return array(
    'vidum' => array(
        'applications' => array(
            'ladoc' => array(
                'name' => 'LADOC',
                'slug' => 'ladoc',
                'directory' => 'Ladoc',
                'show_category_feature_image' => true,
                'show_equipment_feature_image' => true,
                'home' => array(
                    'application' => 'ladoc',
                    'controller' => 'equipment',
                    'action' => 'index'
                ),
                'features' => array(
                    'ladoc-documentation',
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'ladoc' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/ladoc[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'ladoc',
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
