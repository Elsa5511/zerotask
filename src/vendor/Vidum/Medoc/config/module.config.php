<?php

return array(
    'vidum' => array(
        'applications' => array(
            'medoc' => array(
                'name' => 'MEDOC',
                'slug' => 'medoc',
                'directory' => 'Medoc',
                'show_category_feature_image' => true,
                'show_equipment_feature_image' => true,
                'home' => array(
                    'application' => 'medoc',
                    'controller' => 'equipment',
                    'action' => 'index'
                ),
                'features' => array(
                    'documentation',
                    'training',
                    'exercise',
                    'exam',
                    'certification'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'medoc' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/medoc[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'medoc',
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