<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\RestraintCertifiedDocumentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RestraintCertifiedDocumentServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new RestraintCertifiedDocumentService(array(
            'entity_manager' => $entityManager
        ));
    }
}
