<?php

namespace Quiz\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Quiz\Service\QuizService;

abstract class QuizServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {

        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $repositoryAsString = $this->getRepositoryAsString();
        $newEntity = $this->getNewEntity();
        $quizService = new QuizService(
                array(
            'entity_manager' => $entityManager,
            'quiz_repository' => $entityManager->getRepository($repositoryAsString),
            'quiz_repository_string' => $this->getRepositoryAsString(),
            'child_entity' => $newEntity,
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
                )
        );
        return $quizService;
    }

    abstract protected function getRepositoryAsString();

    abstract protected function getNewEntity();
}

?>
