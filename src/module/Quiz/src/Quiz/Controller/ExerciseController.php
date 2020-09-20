<?php

namespace Quiz\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Quiz\Controller\QuizBaseController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

class ExerciseController extends QuizBaseController
{

    protected function getViewTemplatePath()
    {
        return 'quiz/exercise/edit.phtml';
    }

    protected function getQuizService()
    {
        return $this->getService('Quiz\Service\ExerciseService');
    }

    protected function getNewQuiz()
    {
        $equipmentId = (int) $this->params()->fromRoute('equipment_id', 0);

        $exercise = $this->getQuizService()
                ->getNewQuiz($equipmentId);
        return $exercise;
    }

    protected function createQuizForm($formFactory)
    {
        $exerciseForm = $formFactory->createExerciseForm();
        return $exerciseForm;
    }

    protected function getQuizTypeName()
    {
        return $this->getTranslator()->translate('Exercise');
    }

    protected function retrieveQuiz($exerciseId)
    {
        $exercise = $this->getQuizService()->getQuiz($exerciseId);
        return $exercise;
    }
    
    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
                    $actionName = $controller->params()->fromRoute('action');
                    $applicationName = $controller->params()->fromRoute('application');
                    if (in_array($actionName, array('index'))) {
                        $searchForms = $controller->forward()->dispatch('Controller\Equipment', array('action' => 'advanced-search', 'application' => $applicationName));
                        $controller->layout()->addChild($searchForms, 'searchForms');
                    }
                }, -100); // execute after executing action logic

        return $this;
    }
    
    public function indexAction()
    {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The equipment type does not exist'), 'error');
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);
        if($this->getCurrenUser()) {
            $currentUserId = $this->getCurrenUser()->getId();
            $exercises = $this->getExerciseService()->getExercisesWithAttemptsAggregate($currentUserId, $equipmentId);
        } else {
            /* For guest access - se acl.local.php.dist */
            $exercises = new ArrayCollection();
        }

        return new ViewModel(array(
            'title' => $equipment->getTitle() . ': ' . $this->getTranslator()->translate('Exercises'),
            'equipmentId' => $equipmentId,
            'exercises' => $exercises
        ));
    }
    
    public function detailAction()
    {
        $exerciseId = (int) $this->params()->fromRoute('quiz');
        $exercise = $this->retrieveQuiz($exerciseId);

        if ($exercise) {
            $this->setBreadcrumbForFeatureActions($exercise->getEquipment(), $this->getControllerName());
            $questions = $exercise->getQuestions()->getValues();
            $questionNumber = (int) $this->params()->fromRoute('question', 0);
            $questionIndex = $questionNumber - 1;
            $totalQuestionNumber = count($questions);

            $viewValues = array(
                'controller' => 'exercise',
                'exerciseAttemptId' => $exerciseId,
                'title' => $exercise->getName(),
            );
            $isValidQuestionNumber = $questionNumber > 0 && ($questionNumber <= $totalQuestionNumber);
            if ($isValidQuestionNumber) {
                return array_merge($viewValues, array(
                    'questionNo' => $questionNumber,
                    'answerStatuses' => null,
                    'currentQuestionAndAnswer' => $questions[$questionIndex],
                    'totalNoOfQuestions' => $totalQuestionNumber,
                ));
            } else {
                return $viewValues;
            }
        } else {
            $this->sendFlashMessage("Exercise doesn't exist.", "error");
            return $this->redirectToReferer();
        }
    }
    
    protected function getControllerName(){
        return "exercise";
    }
    
    private function getEquipmentService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    private function getExerciseService()
    {
        return $this->getServiceLocator()->get('Quiz\Service\ExerciseService');
    }

}
