<?php

namespace Quiz;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.entitymanager.orm_default' => new \Acl\Factory\AclEntityManagerFactory('orm_default'),
                'Quiz\Form\FormFactory' => function ($serviceManager) {
                    $formFactory = new \Quiz\Form\FormFactory();
                    $formFactory->setTranslator($serviceManager->get('translator'));
                    $formFactory->setObjectManager($serviceManager->get('Doctrine\ORM\EntityManager'));
                    return $formFactory;
                },
                'Quiz\Service\ExerciseService' => 'Quiz\Factory\Service\ExerciseServiceFactory',
                'Quiz\Service\ExerciseAttemptService' => 'Quiz\Factory\Service\ExerciseAttemptServiceFactory',
                'Quiz\Service\ExamService' => 'Quiz\Factory\Service\ExamServiceFactory',
                'Quiz\Service\ExamAttemptService' => 'Quiz\Factory\Service\ExamAttemptServiceFactory',
                'Quiz\Service\QuestionService' => 'Quiz\Factory\Service\QuestionServiceFactory',
                'Quiz\Service\ExerciseAttemptQuestionAndAnswerService' => 'Quiz\Factory\Service\ExerciseAttemptQuestionAndAnswerServiceFactory'
        ));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__),
                ),
            )
        );
    }
}
