<?php

return array(
    'vidum' => array(
        'applications' => array(
            'vopp' => array(
                'name' => 'VOPP',
                'slug' => 'vopp',
                'directory' => 'Vopp',
                'show_category_feature_image' => true,
                'show_equipment_feature_image' => true,
                'home' => array(
                    'application' => 'vopp',
                    'controller' => 'equipment',
                    'action' => 'index'
                ),
                'features' => array(
                    'best_practice',
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'vopp' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/vopp[/:controller[/:action]]',
                    'defaults' => array(
                        'application' => 'vopp',
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