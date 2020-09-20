<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\LadocDocumentationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LadocDocumentationServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new LadocDocumentationService(array(
            'entity_manager' => $entityManager
        ));
    }
}
