<?php

namespace Quiz\Controller;

class ExerciseAttemptController extends QuizAttemptController {

    public function indexAction() {
        $attemptId = (int) $this->params()->fromRoute('quiz');
        $attemptService = $this->getQuizAttemptService();
        $quizAttempt = $attemptService->findById($attemptId);
        if ($quizAttempt) {
            $this->setBreadcrumbForFeatureActions($quizAttempt->getQuiz()->getEquipment(), 'exercise');
            return $this->displayIndexView($quizAttempt);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToPath("index");
        }
    }

    public function startAction() {
        $exerciseId = (int) $this->params()->fromRoute('quiz', 0);
        $exercise = $this->getExerciseService()->getQuiz($exerciseId);

        if ($exerciseId > 0) {
            $questions = $exercise->getQuestions();
            if (count($questions) < 1) {
                $eqipmentId = $exercise->getEquipment()->getEquipmentId();
                $this->sendTranslatedFlashMessage($this->getTranslator()->translate('This exercise does not have any questions.'), 'error');
                return $this->redirectToPath('quiz', 'index', array('id' => $eqipmentId));
            }

            $userId = $this->getCurrenUser()->getId();
            $exerciseAttempt = $this->getQuizAttemptService()
                    ->createNewAttempt($exerciseId, $userId);

            $getQuestionParams = array(
                'quiz' => $exerciseAttempt->getAttemptId(),
                'question' => 1,
            );

            return $this->redirectToPath('exercise-attempt', 'index', $getQuestionParams);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function continueAction() {
        $exerciseId = $this->params()->fromRoute('quiz');
        $exerciseAttemptService = $this->getQuizAttemptService();
        $exerciseAttempt = $exerciseAttemptService->findAttemptInProgress($exerciseId);

        if ($exerciseAttempt !== null) {
            $getQuestionParams = array(
                'question' => 1,
                'quiz' => $exerciseAttempt->getAttemptId()
            );
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
        return $this->redirectToPath('exercise-attempt', 'index', $getQuestionParams);
    }

    public function restartAction() {
        $exerciseId = $this->params()->fromRoute('quiz');
        $exerciseAttemptService = $this->getQuizAttemptService();
        $exerciseAttempt = $exerciseAttemptService->findAttemptInProgress($exerciseId);
        if ($exerciseAttempt !== null) {
            $exerciseAttemptService->remove($exerciseAttempt);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
        return $this->redirectToPath('exercise-attempt', 'start', array('quiz' => $exerciseId));

    }

    /**
     * @return Form
     */
    protected function getQuizSearchForm() {
        $formFactory = $this->getFormFactory("Quiz");
        $form = $formFactory->createExerciseSearchForm();
        return $form;
    }

    private function getExerciseService() {
        return $this->getService('Quiz\Service\ExerciseService');
    }

    protected function getQuizAttemptService() {
        return $this->getService('Quiz\Service\ExerciseAttemptService');
    }

    protected function getControllerName() {
        return "exercise-attempt";
    }
    
    protected function getParentControllerName(){
        return "exercise";
    }

    protected function getIndexViewValues($exerciseAttempt) {
        $exerciseAttempt = null; // This is here only to get rid of the "unused variable" warning
        return array('attemptType' => 'exercise');
    }

}
