<?php

/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'bjyauthorize' => array(
        // default role for unauthenticated users
        'default_role' => 'guest',
        // default role for authenticated users (if using the
        // 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider' identity provider)
        'authenticated_role' => 'user',
        // identity provider service name
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        // Role providers to be used to load all available roles into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'role_providers' => array(
            /* here, 'guest' and 'user are defined as top-level roles, with
             * 'admin' inheriting from user
             */
            /* 'BjyAuthorize\Provider\Role\Config' => array(
              'guest' => array(),
              'user' => array('children' => array(
              'admin' => array(),
              )),
              ),
              // this will load roles from the user_role table in a database
              // format: user_role(role_id(varchar), parent(varchar))
              'BjyAuthorize\Provider\Role\ZendDb' => array(
              'table' => 'user_role',
              'role_id_field' => 'role_id',
              'parent_role_field' => 'parent_id',
              ), */
            // this will load roles from
            // the 'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' service
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                // class name of the entity representing the role
                'role_entity_class' => 'Application\Entity\Role',
                // service name of the object manager
                /*                'object_manager' => 'Doctrine\Common\Persistence\ObjectManager', */
                'object_manager' => 'doctrine.entitymanager.orm_default',
            ),
        ),
        // Resource providers to be used to load all available resources into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'admin' => array(),
            ),
        ),
        // Rule providers to be used to load all available rules into Zend\Permissions\Acl\Acl
        // Keys are the provider service names, values are the options to be passed to the provider
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    // allow guests and users (and admins, through inheritance)
                    // the "wear" privilege on the resource "pants"
                    array(array('admin'), 'admin', 'view')
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                // ...
                ),
            ),
        ),
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
            ),
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                
            ),
        ),
        /* // strategy service name for the strategy listener to be used when permission-related errors are detected
          'unauthorized_strategy' => 'BjyAuthorize\View\UnauthorizedStrategy',
          // Template name for the unauthorized strategy
          'template' => 'error/403', */
        'unauthorized_strategy' => 'Application\View\MixedStrategy',
    ),
    'zenddevelopertools' => array(
        'profiler' => array(
            'collectors' => array(
                'bjy_authorize_role_collector' => 'BjyAuthorize\Collector\RoleCollector',
            ),
        ),
        'toolbar' => array(
            'entries' => array(
                'bjy_authorize_role_collector' => 'zend-developer-tools/toolbar/bjy-authorize-role',
            ),
        ),
    ),
);
