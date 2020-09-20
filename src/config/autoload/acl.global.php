<?php

return array(
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    
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
                    'controller' => 'zfcuser',
                    'action' => array('index', 'logout'),
                    'roles' => array('user', 'admin', 'guest')
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => array('login'),
                    'roles' => array('guest')
                )
            ),
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'vedos', 'roles' => array('user')),
                array('route' => 'vedos/wildcard', 'roles' => array('user')),
                array('route' => 'medoc', 'roles' => array('user')),
                array('route' => 'medoc/wildcard', 'roles' => array('user')),
                array('route' => 'ladoc', 'roles' => array('user')),
                array('route' => 'ladoc/wildcard', 'roles' => array('user')),
                array('route' => 'vopp', 'roles' => array('user')),
                array('route' => 'vopp/wildcard', 'roles' => array('user')),
                array('route' => 'liftdoc', 'roles' => array('user')),
                array('route' => 'liftdoc/wildcard', 'roles' => array('user')),
                array('route' => 'falldoc', 'roles' => array('user')),
                array('route' => 'falldoc/wildcard', 'roles' => array('user')),
                array('route' => 'zfcuser', 'roles' => array('user', 'admin')),
                array('route' => 'zfcuser/logout', 'roles' => array('user', 'admin', 'guest')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'home', 'roles' => array('user', 'admin')),
                array('route' => 'user', 'roles' => array('user', 'admin', 'guest')),
                array('route' => 'user/wildcard', 'roles' => array('user', 'admin', 'guest')),
                array('route' => 'base', 'roles' => array('user', 'admin', 'guest')),
                array('route' => 'base/wildcard', 'roles' => array('user', 'admin', 'guest')),
            )
        )
    )
);
