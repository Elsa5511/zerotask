<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonQuiz for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'doctrine' => array(
        'driver' => array(
            'test_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Quiz/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Quiz\Entity' => 'test_entities'
                )
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Controller\Exercise' => 'Quiz\Controller\ExerciseController',
            'Controller\ExerciseAttempt' => 'Quiz\Controller\ExerciseAttemptController',
            'Controller\Exam' => 'Quiz\Controller\ExamController',
            'Controller\Question' => 'Quiz\Controller\QuestionController',
            'Controller\ExerciseAttempt' => 'Quiz\Controller\ExerciseAttemptController',
            'Controller\ExamAttempt' => 'Quiz\Controller\ExamAttemptController'
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'bjyauthorize' => array(
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'Quiz\Entity\Exam' => array(),
                'Quiz\Entity\ExamAttempt' => array(),
                'Quiz\Entity\ExamAttemptQuestionAndAnswers' => array(),
                'Quiz\Entity\Exercise' => array(),
                'Quiz\Entity\ExerciseAttempt' => array(),
                'Quiz\Entity\ExerciseAttemptQuestionAndAnswers' => array(),
                'Quiz\Entity\Option' => array(),
                'Quiz\Entity\Question' => array(),
                'Quiz\Entity\Quiz' => array(),
                'Quiz\Entity\QuizAttempt' => array(),
                'Quiz\Entity\QuizAttemptQuestionAndAnswers' => array(),
                'Quiz\Entity\QuizAttemptStatusOverview' => array(),
                'Quiz\Entity\QuizWithAttemptsAggregate' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array('user', 'Quiz\Entity\Exam', array('read')),
                    array('user', 'Quiz\Entity\ExamAttempt', array('read', 'update')),
                    array('user', 'Quiz\Entity\ExamAttemptQuestionAndAnswers', array('read', 'update', 'delete')),
                    array('user', 'Quiz\Entity\Exercise', array('read')),
                    array('user', 'Quiz\Entity\ExerciseAttempt', array('read', 'create', 'update')),
                    array('user', 'Quiz\Entity\ExerciseAttemptQuestionAndAnswers', array('read', 'create', 'update', 'delete')),
                    array('user', 'Quiz\Entity\Option', array('read')),
                    array('user', 'Quiz\Entity\Question', array('read')),
                    array('user', 'Quiz\Entity\Quiz', array('read')),
                    array('user', 'Quiz\Entity\QuizAttempt', array('read')),
                    array('user', 'Quiz\Entity\QuizAttemptQuestionAndAnswers', array('read')),
                    array('user', 'Quiz\Entity\QuizAttemptStatusOverview', array('read')),
                    array('user', 'Quiz\Entity\QuizWithAttemptsAggregate', array('read')),
                    
                    array('admin', 'Quiz\Entity\Exam', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\ExamAttempt', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\ExamAttemptQuestionAndAnswers', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\Exercise', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\ExerciseAttempt', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\ExerciseAttemptQuestionAndAnswers', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\Option', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\Question', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\Quiz', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\QuizAttempt', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\QuizAttemptQuestionAndAnswers', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\QuizAttemptStatusOverview', array('create', 'update', 'delete')),
                    array('admin', 'Quiz\Entity\QuizWithAttemptsAggregate', array('create', 'update', 'delete')),
                ),
                // Don't mix allow/deny rules if you are using role inheritance.
                // There are some weird bugs.
                'deny' => array(
                ),
            ),
        ),
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Controller\Exercise',
                    'action' => array(
                        'add', 'edit', 'delete', 'detail',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Exercise',
                    'action' => array(
                        'index',
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\Exam',
                    'action' => array(
                        'add', 'edit', 'delete', 'questions',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\Exam',
                    'action' => array(
                        'index',
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\Question',
                    'action' => array(
                        'add', 'edit', 'delete',
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\ExerciseAttempt',
                    'action' => array(
                        'start', 'index', 'continue', 'restart', 'validate-answer', 'complete-attempt'
                    ),
                    'roles' => array('admin', 'user')
                ),
                array(
                    'controller' => 'Controller\ExerciseAttempt',
                    'action' => array(
                        'report', 'export-report'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\ExamAttempt',
                    'action' => array(
                        'report', 'add', 'delete', 'admin', 'export-report'
                    ),
                    'roles' => array('admin')
                ),
                array(
                    'controller' => 'Controller\ExamAttempt',
                    'action' => array(
                        'index', 'start', 'validate-answer', 'complete-attempt'
                    ),
                    'roles' => array('admin', 'user')
                ),
            )
        )
    ),
);
