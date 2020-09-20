<?php

namespace Quiz\Factory\Service;

use Quiz\Service\ExerciseAttemptService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExerciseAttemptServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $service = new ExerciseAttemptService(array(
            'entity_manager' => $entityManager,
            'exercise_attempt_repository' => $entityManager->getRepository('Quiz\Entity\ExerciseAttempt'),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));
        return $service;
    }
}
