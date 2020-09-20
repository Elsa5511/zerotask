<?php

namespace Quiz\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Quiz\Service\QuizService;
use Zend\View\Model\ViewModel;
use Quiz\Controller\QuizBaseController;

class ExamController extends QuizBaseController
{

    protected function getNewQuiz()
    {
        $equipmentId = (int) $this->params()->fromRoute('equipment_id', 0);

        $exam = $this->getQuizService()
                ->getNewQuiz($equipmentId);
        
        return $exam;
    }

    protected function createQuizForm($formFactory)
    {
        $equipmentId = (int) $this->params()->fromRoute('equipment_id', 0);

        $examForm = $formFactory->createExamForm($equipmentId);
        return $examForm;
    }

    protected function getQuizTypeName()
    {
        return $this->getTranslator()->translate('Exam');
    }

    protected function retrieveQuiz($exercieId)
    {
        $exam = $this->getQuizService()->getQuiz($exercieId);
        return $exam;
    }
    
    public function indexAction()
    {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The equipment type does not exist'), 'error');
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForFeatureActions($equipment, 'exam');
        if($this->getCurrenUser()) {
            $currentUserId = $this->getCurrenUser()->getId();
            $exams = $this->getExamService()->getQuizzes(array("equipment" => $equipmentId));
            $examIds = $this->getExamAttemptService()->getExamIds($exams);
            $examAttempts = $this->getExamAttemptService()->findUserAttempts($currentUserId, $examIds);

            $notExpiredExamAttempts = array();
            foreach ($examAttempts as $examAttempt) {
                if (!$examAttempt->isExpired()) {
                    array_push($notExpiredExamAttempts, $examAttempt);
                }
            }
        } else {
            /* For guest access - se acl.local.php.dist */
            $notExpiredExamAttempts = new ArrayCollection();
            $exams = new ArrayCollection();
        }



        $view = new ViewModel(array(
            'title' => $equipment->getTitle() . ': ' . $this->getTranslator()->translate('Tests'),
            'equipmentId' => $equipmentId,
            'examAttempts' => $notExpiredExamAttempts,
            'exams' => $exams
        ));
        
        return $view;
    }
    
    public function questionsAction()
    {
        $this->layout("layout/blank");
        $exerciseId = (int) $this->params()->fromRoute('id', 0);
        $questions = array();

        if ($exerciseId > 0) {
            $questions = $this->getQuestionService()->getQuestionsByExercise($exerciseId);
        }
        return array(
            "questions" => $questions
        );
    }
    
    protected function storePostData($postData, $quizForm, $action)
    {
        $quizForm->setData($postData);
        $quiz = $quizForm->getObject();
        $questionsArray = $postData->get("questions");

        if ($quizForm->isValid()) {
            $this->saveExamData($quiz, $questionsArray);
            return $this->redirectTo('index', array('id' => $quiz->getEquipment()->getEquipmentId()));
        } else {
            return $this->displayForm($quizForm, $action, $postData);
        }
    }
    
    private function saveExamData($exam, $questionsArray)
    {
        $quizId = $this->getQuizService()->persistExamData($exam, $questionsArray);

        if ($quizId > 0) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The exam has been successfully saved."));
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The exam could not be saved. Try again later."), "error");
        }
    }
    
    protected function displayForm($quizForm, $action = 'add', $post = null)
    {
        $questions = null;
        $questionsIds = array();
        $exerciseId = 0;
        $quizForm->prepare();
            
        if($post) {
            $examFieldset = $post->get("exam");
            $exerciseId = $examFieldset["baseOnPracticeExercise"];
            $questionsIds = ($post->get("questions")) ? $post->get("questions") : array();
        } else {
            $exam = $quizForm->getObject();
            if($exam) {                
                $exerciseId = $exam->getExerciseId();
                $questionsIds = $exam->getQuestionsIds();                
            }
        }

        if ($exerciseId > 0) {
            $questions = $this->getQuestionService()->getQuestionsByExercise($exerciseId);
        }
        
        $view = new ViewModel(array(
            'form' => $quizForm,
            'questions' => $questions,
            'questionsIds' => $questionsIds,
            'action' => $action
        ));
        $view->setTemplate($this->getViewTemplatePath());
        return $view;
    }
    
    protected function getViewTemplatePath()
    {
        return 'quiz/exam/edit.phtml';
    }

    protected function getQuizService()
    {
        return $this->getService('Quiz\Service\ExamService');
    }

    private function getQuestionService()
    {
        return $this->getService('Quiz\Service\QuestionService');
    }
    
    private function getEquipmentService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }
    
    private function getExamAttemptService()
    {
        return $this->getServiceLocator()->get('Quiz\Service\ExamAttemptService');
    }

    /**
     * @return QuizService
     */
    private function getExamService()
    {
        return $this->getServiceLocator()->get('Quiz\Service\ExamService');
    }
    
    protected function getControllerName(){
        return "exam";
    }

}
