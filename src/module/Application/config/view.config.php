<?php

use Application\View\Helper\Application;

return array(
    'factories' => array(
        'Application' => function($sm) {
            $routeMatch = $sm->getServiceLocator()->get('router')->match($sm->getServiceLocator()->get('request'));
            return new Application($routeMatch, $sm->getServiceLocator());
        }
    )
);