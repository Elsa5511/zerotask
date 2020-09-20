<?php
namespace Equipment\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Equipment\Service\CheckinService;

class CheckinServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        
        $checkinService = new CheckinService(array(
            'entity_manager' => $entityManager,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));
        
        return $checkinService;
    }
}
