<?php
namespace LadocDocumentation\Controller\Helper;

use Zend\EventManager\EventManagerInterface;

class CustomEventManagerClass {

	public static function addDescriptionDispatchEvent(EventManagerInterface $events, $controller)
	{
        $events->attach('dispatch', function ($e) use ($controller) {
            $actionName = $controller->params()->fromRoute('action');
            $documentationId = $controller->params()->fromRoute('documentation_id', 0);
            $applicationName = $controller->params()->fromRoute('application');
            if (in_array($actionName, array('index'))) {
                $descriptionForm = $controller->forward()->dispatch('Controller\LadocDocumentation', 
                    array(
                        'action' => 'description', 
                        'application' => $applicationName, 
                        'documentation_id' => $documentationId,
                        'type' => $controller->getControllerName()
                    )
                );
                $controller->layout()->addChild($descriptionForm, 'descriptionForm');
            }
        }, -100); // execute after executing action logic
	}
}