<?php

namespace Application\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class SectionServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $repositoryAsString = $this->getRepositoryAsString();
        $sectionService = new \Application\Service\SectionService(
                array(
            'entity_manager' => $entityManager,
            'section_repository' => $entityManager->getRepository($repositoryAsString),
            'section_repository_string' => $repositoryAsString,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
                )
        );
        return $sectionService;
    }

    abstract protected function getRepositoryAsString();
}

?>
