<?php

namespace Equipment\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PeriodicControlAttachmentServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Vidum\Config');
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $repositoryAsString = 'Equipment\Entity\PeriodicControlAttachment';
        $equipmentAttachmentService = new \Equipment\Service\PeriodicControlAttachmentService(
            array(
                'entity_manager' => $entityManager,
                'image' => $config['image'],
                'attachment_repository' => $entityManager->getRepository($repositoryAsString),
                'attachment_repository_string' => $repositoryAsString,
                'dependencies' => array(
                    'translator' => $serviceLocator->get('translator')
                )
            )
        );
        return $equipmentAttachmentService;
    }

   

}