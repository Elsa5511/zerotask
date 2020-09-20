<?php

namespace LadocDocumentation\Factory\Service;

use LadocDocumentation\Service\LadocDocumentationAttachmentService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LadocDocumentationAttachmentServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return new LadocDocumentationAttachmentService(array(
            'entity_manager' => $entityManager
        ));
    }
}
