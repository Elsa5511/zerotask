<?php

namespace Quiz\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;
use Quiz\Service\QuizAnswerValidator;

abstract class QuizAttemptController extends AbstractBaseController {

    public abstract function startAction();

    protected abstract function getIndexViewValues($quizAttempt);

    protected abstract function getQuizAttemptService();
    
    protected abstract function getControllerName();
    
    protected abstract function getParentControllerName();

    /**
     * 
     * @return array View values
     */
    public function reportAction() {
        $searchForm = $this->getQuizSearchForm();

        $request = $this->getRequest();
        $isSearch = $request->isPost();
        if ($isSearch) {
            $post = $request->getPost();
            $filterParams = $post->get('quiz-search');
            if (is_null($filterParams)) {
                $filterParams = array();
            }
            $searchForm->setData($post);

            $quizAttempts = $this->getQuizAttemptService()->search($filterParams);
        } else {
            $filterParams = array();
            $quizAttempts = $this->getQuizAttemptService()->findAll();
        }

        return array(
            'attempts' => $quizAttempts,
            'searchForm' => $searchForm,
            'isSearch' => $isSearch,
            'currentFilterParams' => $filterParams
        );
    }

    public function exportReportAction() {
        $post = $this->getRequest()->getPost();
        $filterParams = $post->get('filter-params');
        $type = $post->get('exportType');
        if ($filterParams === null) {
            $quizAttempts = $this->getQuizAttemptService()->findAll();
        } else {
            $quizAttempts = $this->getQuizAttemptService()->search($filterParams);
        }
        $report = $this->getQuizAttemptService()->createReportTable($quizAttempts);
        $this->exportReport($report, $type);
        return $this->response;
    }

    public function completeAttemptAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $quizAttemptId = $request->getPost('quizAttemptId');
            $attemptService = $this->getQuizAttemptService();
            $quizAttempt = $attemptService->findById($quizAttemptId);
            if ($quizAttempt !== null) {
                $attemptService->completeAttempt($quizAttempt);
                $quiz = $quizAttempt->getQuiz();
                $equipmentId = $quiz->getEquipment()->getEquipmentId();
                $this->redirectToPath($this->getParentControllerName(), 'index', array('id' => $equipmentId));
            } else {
                $this->displayGenericErrorMessage();
            }
        }
    }

    public function validateAnswerAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $quizAttemptId = $request->getPost('quizAttemptId');
            $quizAttemptService = $this->getQuizAttemptService();
            $quizAttempt = $quizAttemptService->findById($quizAttemptId);
            $questionNo = (int) $this->params()->fromRoute('question', 1);
            $questionIndex = $questionNo - 1;
            $currentQuestionAndAnswers = $this->getQuestionAndAnswersFrom($quizAttempt, $questionIndex);
            $showStatusMessage = 0;

            if (!$this->canSubmitAnswer($currentQuestionAndAnswers)) {
                return $this->redirectToIndex($questionNo, $quizAttemptId);
            } else {
                $this->saveAnswers($quizAttempt, $questionIndex);
                $showStatusMessage = 1;
            }

            return $this->redirectToIndex($questionNo, $quizAttemptId, $showStatusMessage);
        }
    }

    protected function canSubmitAnswer($currentQuestionAndAnswers) {
        if (count($currentQuestionAndAnswers->getSelectedOptions()) > 0) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('This question has already been answered.'), 'error');
            return false;
        } else {
            return true;
        }
    }

    private function saveAnswers($quizAttempt, $questionIndex) {
        $currentQuestionAndAnswers = $this->getQuestionAndAnswersFrom($quizAttempt, $questionIndex);
        $selectedOptionIndexes = $this->getRequest()->getPost('option');
        $selectedOptions = $this->getSelectedOptionsFrom($currentQuestionAndAnswers, $selectedOptionIndexes);
        $currentQuestionAndAnswers->setSelectedOptions($selectedOptions);
        $attemptService = $this->getQuizAttemptService();
        $attemptService->persist($quizAttempt);
    }

    protected function getQuestionAndAnswersFrom($quizAttempt, $questionIndex) {
        $questionsAndAnswers = $quizAttempt->getQuestionsAndAnswers();
        return $questionsAndAnswers[$questionIndex];
    }

    protected function getSelectedOptionsFrom($questionAndAnswer, $selectedOptionIndexes) {
        $question = $questionAndAnswer->getQuestion();
        $options = $question->getOptions();
        $selectedOptions = new ArrayCollection();
        foreach ($selectedOptionIndexes as $index) {
            $selectedOptions->add($options[$index]);
        }
        return $selectedOptions;
    }

    protected function displayIndexView($quizAttempt) {
        $questionNumber = (int) $this->params()->fromRoute('question', 1);
        $quizAttemptService = $this->getQuizAttemptService();
        $title = $quizAttempt->getQuiz()->getName();
        $questionsAndAnswers = $quizAttempt->getQuestionsAndAnswers();
        $answerStatuses = $quizAttemptService->getAnswerStatuses($questionsAndAnswers, new QuizAnswerValidator());
        $questionIndex = $questionNumber - 1;
        $currentQuestionAndAnswers = $questionsAndAnswers[$questionIndex];
        $statusOverview = $quizAttemptService->createStatusOverview($quizAttempt);
        $showStatusMessage = $this->params()->fromRoute('msg');

        $viewValues = array(
            'controller' => $this->getControllerName(),
            'questionNo' => $questionNumber,
            'exerciseAttemptId' => $quizAttempt->getAttemptId(),
            'title' => $title,
            'answerStatuses' => $answerStatuses,
            'currentQuestionAndAnswers' => $currentQuestionAndAnswers,
            'totalNoOfQuestions' => count($questionsAndAnswers),
            'currentAnswerStatus' => $answerStatuses[$questionIndex],
            'statusOverview' => $statusOverview,
            'showStatusMessage' => $showStatusMessage,
        );
        $mergedViewValues = array_merge($viewValues, $this->getIndexViewValues($quizAttempt));

        $view = new ViewModel($mergedViewValues);
        $view->setTemplate('quiz/quiz-attempt/index.phtml');
        return $view;
    }

    protected function redirectToIndex($questionNo, $quizAttemptId, $showStatusMessage = 0) {
        $indexParams = array(
            'question' => $questionNo,
            'quiz' => $quizAttemptId,
            'msg' => $showStatusMessage,
        );

        $controller = $this->getControllerName();
        return $this->redirectToPath($controller, 'index', $indexParams);
    }

}
