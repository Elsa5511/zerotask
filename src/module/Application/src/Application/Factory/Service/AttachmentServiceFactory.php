<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AttachmentServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Vidum\Config');
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $repositoryAsString = $this->getRepositoryAsString();
        $equipmentAttachmentService = new \Application\Service\AttachmentService(
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

    abstract protected function getRepositoryAsString();
}

?>
