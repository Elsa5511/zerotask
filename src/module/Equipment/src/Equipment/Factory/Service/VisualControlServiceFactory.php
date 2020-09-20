<?php
namespace Equipment\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Equipment\Service\VisualControlService;

class VisualControlServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        
        $visualControlService = new VisualControlService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));
        
        return $visualControlService;
    }
}
