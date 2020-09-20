<?php

namespace Quiz\Factory\Service;

use Quiz\Service\ExamAttemptService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExamAttemptServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $service = new ExamAttemptService(array(
            'entity_manager' => $entityManager,
            'exam_attempt_repository' => $entityManager->getRepository('Quiz\Entity\ExamAttempt'),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
        ));
        return $service;
    }
}
