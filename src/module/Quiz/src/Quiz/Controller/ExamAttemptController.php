<?php

namespace Quiz\Controller;

class ExamAttemptController extends QuizAttemptController {

    public function startAction() {
        $examAttemptId = (int) $this->params()->fromRoute('quiz');
        $attemptService = $this->getQuizAttemptService();

        $examAttempt = $attemptService->findById($examAttemptId);
        if ($examAttempt !== null) {
            if ($examAttempt->isExpired()) {
                $this->sendTranslatedFlashMessage($this->translate('The exam attempt has expired. Ask your superior for a new attempt.'), 'error');
                return $this->redirectToReferer();
            }
            if ($examAttempt->isInProgress()) {
                $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Exam has already been started.'), 'error');
                return $this->redirectToReferer();
            } else {
                $attemptService->startAttempt($examAttempt);
                $attemptId = $examAttempt->getAttemptId();
                return $this->redirectToPath('exam-attempt', 'index', array('quiz' => $attemptId));
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function indexAction() {
        $attemptId = (int) $this->params()->fromRoute('quiz');
        $attemptService = $this->getQuizAttemptService();
        $quizAttempt = $attemptService->findById($attemptId);
        if ($quizAttempt) {
            if ($quizAttempt->isExpired()) {
                $this->sendTranslatedFlashMessage($this->translate('The exam attempt has expired. Ask your superior for a new attempt.'), 'error');
                return $this->redirectToReferer();
            }
            $this->setBreadcrumbForFeatureActions($quizAttempt->getQuiz()->getEquipment(), 'exam');
            return $this->displayIndexView($quizAttempt);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToPath("index");
        }
    }

    /**
     * 
     * @return type
     */
    public function addAction() {
        $examAttempt = $this->getQuizAttemptService()->getNewExamAttempt();
        if ($examAttempt) {
            $form = $this->getExamAttemptForm($examAttempt);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                $form->setData($post);

                if ($form->isValid()) {
                    if (empty($post['exam-attempt']['expirationDate'])) {
                        $examAttempt->setExpirationDate(null);
                    }
                    return $this->storePostData($examAttempt);
                }
            }
            return array('form' => $form);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    /**
     * 
     * @param type $post
     * @param type $form
     * @return \Zend\View\Model\ViewModel
     */
    private function storePostData($examAttempt) {
        $exam = $examAttempt->getQuiz();
        $exercise = $exam->getBaseOnPracticeExercise();
        if ($exercise->hasQuestions()) {

            $resultId = $this->getQuizAttemptService()->createExamAttempt($examAttempt);
            if ($resultId > 0) {
                $this->sendTranslatedFlashMessage($this->getTranslator()->translate('An exam attempt has been added successfully.'));
            } else {
                $this->displayGenericErrorMessage();
            }
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The exam doesn't have any questions"), "error");
        }

        return $this->redirectToPath("exam-attempt", "admin");
    }

    public function deleteAction() {
        $examAttemptId = $this->params()->fromRoute('id', null);

        try {
            $this->getQuizAttemptService()->deleteById($examAttemptId);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The exam attempt has been deleted successfully"));
        } catch (\Exception $exception) {
            $this->sendFlashMessage($exception->getMessage(), "error");
        }

        return $this->redirectToPath("exam-attempt", "admin");
    }

    public function adminAction() {
        return $this->reportAction();
    }

    /**
     * 
     * @param type $attempt
     * @return Form
     */
    private function getExamAttemptForm($attempt) {
        $formFactory = $this->getFormFactory("Quiz");
        $form = $formFactory->createExamAttemptForm($this->getApplicationName());
        $form->bind($attempt);
        return $form;
    }

    /**
     * 
     *
     * @return Form
     */
    protected function getQuizSearchForm() {
        $formFactory = $this->getFormFactory("Quiz");
        $form = $formFactory->createExamSearchForm();
        return $form;
    }

    protected function getQuizAttemptService() {
        return $this->getService('Quiz\Service\ExamAttemptService');
    }

    protected function getControllerName() {
        return "exam-attempt";
    }
    
    protected function getParentControllerName(){
        return "exam";
    }

    protected function getIndexViewValues($examAttempt) {
        $exam = $examAttempt->getQuiz();
        $timeLimitInMinutes = $exam->getTimeLimit();
        $startTime = $examAttempt->getStartTime();
        $startTimeInSeconds = $startTime->getTimeStamp();
        $endTimeInSeconds = $startTimeInSeconds + ($timeLimitInMinutes * 60);
        $totalTimeInSeconds = $endTimeInSeconds - $startTimeInSeconds;
        $now = new \Sysco\Aurora\Stdlib\DateTime();
        $nowInSeconds = $now->getTimeStamp();
        $timeLeftInSeconds = $endTimeInSeconds - $nowInSeconds;

        return array(
            'attemptType' => 'exam',
            'totalTimeInSeconds' => $totalTimeInSeconds,
            'timeLeftInSeconds' => $timeLeftInSeconds
        );
    }

    // Override
    protected function canSubmitAnswer($currentQuestionAndAnswers) {
        $examAttempt = $currentQuestionAndAnswers->getQuizAttempt();
        if ($this->timeHasExpired($examAttempt)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("You have exceeded the time limit for this exam. You may no longer submit answers to questions."), "error");
            return false;
        } else {
            return parent::canSubmitAnswer($currentQuestionAndAnswers);
        }
    }

    private function timeHasExpired($examAttempt) {
        $now = new \Sysco\Aurora\Stdlib\DateTime();
        return ($now > $this->getExpirationTimeFor($examAttempt));
    }

    private function getExpirationTimeFor($examAttempt) {
        $exam = $examAttempt->getQuiz();
        $timeLimit = $exam->getTimeLimit();
        $endTime = clone $examAttempt->getStartTime();
        $endTime->add(new \DateInterval('PT' . $timeLimit . 'M'));
        return $endTime;
    }

}
