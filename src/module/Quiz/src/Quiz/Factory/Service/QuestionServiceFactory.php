<?php

namespace Quiz\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Quiz\Service\QuestionService;

class QuestionServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $config = $serviceLocator->get('Vidum\Config');
        $questionService = new QuestionService(
                array(
            'entity_manager' => $entityManager,
            'image' => $config['image'],
            'question_repository' => $entityManager->getRepository('Quiz\Entity\Question'),
            'dependencies' => array(
                'translator' => $serviceLocator->get('translator')
            )
                )
        );
        return $questionService;
    }

}