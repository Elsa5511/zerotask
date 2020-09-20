<?php

return array(
    'vidum' => array(
        'applications' => array(
            'liftdoc' => array(
                'name' => 'LIFTDOC',
                'slug' => 'liftdoc',
                'directory' => 'Liftdoc',
                'show_category_feature_image' => true,
                'show_equipment_feature_image' => true,
                'home' => array(
                    'application' => 'liftdoc',
                    'controller' => 'equipment',
                    'action' => 'index'
                ),
                'features' => array(
                    'documentation'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'liftdoc' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/liftdoc[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'liftdoc',
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