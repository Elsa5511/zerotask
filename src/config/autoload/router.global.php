<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Controller\Application',
                        'action' => 'index',
                    ),
                ),
            ),
            'base' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/:application[/:controller[/:action]]',
                    'defaults' => array(
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
                    )
                ),
            ),
            'application' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/application[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Controller',
                        'controller' => 'Application',
                        'action' => 'index'
                    ),
                    'constraints' => array(
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
            
            'user' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Controller',
                        'controller' => 'User',
                        'action' => 'index'
                    ),
                    'constraints' => array(
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
        ),
    )
);
