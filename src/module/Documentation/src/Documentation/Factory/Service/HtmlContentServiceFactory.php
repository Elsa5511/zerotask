<?php

namespace Documentation\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class HtmlContentServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $repositoryAsString = $this->getRepositoryAsString();
        $htmlContentService = new \Documentation\Service\HtmlContentService(
                array(
            'entity_manager' => $entityManager,
            'html_content_repository' => $entityManager->getRepository($repositoryAsString),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
                )
        );
        return $htmlContentService;
    }

    abstract protected function getRepositoryAsString();
}

?>