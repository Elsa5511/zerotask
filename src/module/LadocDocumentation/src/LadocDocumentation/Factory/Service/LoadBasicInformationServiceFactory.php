<?php

namespace LadocDocumentation\Factory\Service;


use LadocDocumentation\Service\LoadBasicInformationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class LoadBasicInformationServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        return new LoadBasicInformationService(array(
            'entity_manager' => $entityManager
        ));
    }
}
