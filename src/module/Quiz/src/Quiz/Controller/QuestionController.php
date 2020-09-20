<?php

namespace Quiz\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;
use Quiz\Entity\QuizAttempt;
use Zend\Stdlib\Parameters;

class QuestionController extends AbstractBaseController
{

    const IMAGE_REMOVED = 1;

    public function addAction()
    {
        $question = $this->tryToGetNewQuestion();
        if ($question == null) {
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForQuestions($question);

        $questionForm = $this->createAndBindQuestionForm($question);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );

            return $this->storePostData($post, $questionForm);
        } else {
            return $this->displayForm($questionForm);
        }
    }

    public function editAction()
    {
        $questionId = $this->params()->fromRoute('id', null);
        $question = $this->getQuestionService()->findById($questionId);
        if ($question !== null) {
            $this->setBreadcrumbForQuestions($question);
            $questionForm = $this->createAndBindQuestionForm($question);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = array_merge_recursive(
                        $request->getPost()->toArray(), $request->getFiles()->toArray()
                );
                return $this->storePostData($post, $questionForm);
            } else {
                return $this->displayForm($questionForm, 'edit', $question->getImage());
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    public function deleteAction()
    {
        $questionId = $this->params()->fromRoute('id', null);

        try {
            $exerciseId = $this->getQuestionService()->deleteById($questionId);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The question has been deleted successfully"));
        } catch (\Exception $exception) {
            $exerciseId = 0;
            $this->sendFlashMessage($exception->getMessage(), "error");
            $this->redirectToReferer();
        }

        return $this->redirectToIndex($exerciseId);
    }

    private function tryToGetNewQuestion()
    {
        try {
            return $this->getNewQuestion();
        } catch (\Exception $exception) {
            $this->sendFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    private function getNewQuestion()
    {
        $exerciseId = (int) $this->params()->fromRoute('quiz', 0);
        $question = $this->getQuestionService()
                            ->getNewQuestion($exerciseId);
        return $question;
    }

    private function createAndBindQuestionForm($question)
    {
        $formFactory = $this->getFormFactory('Quiz');
        $questionForm = $formFactory->createQuestionForm();
        $questionForm->bind($question);
        return $questionForm;
    }

    private function storePostData($postData, $questionForm)
    {
        $questionForm->setData($postData);

        if ($questionForm->isValid()) {
            $question = $questionForm->getObject();

            if($this->getQuestionService()->isAbleToEditQuestion($question)){
                if (self::IMAGE_REMOVED == $postData["question"]["remove_image"]) {
                    $this->getQuestionService()->removeQuestionImage($question->getImage());
                    $question->setImage(null);
                }

                return $this->saveQuestionData($question, $postData["question"]["question_image"]);
            }
            $message = $this->getTranslator()->translate('You cannot edit this question because it is used in ongoing exam attempts');
            $this->sendTranslatedFlashMessage($message, 'error');
        }
        return $this->displayForm($questionForm);
    }

    private function saveQuestionData($question, $image)
    {
        $data = array("quiz" => array($question->getExerciseId()),
                    "status" => QuizAttempt::STATUS_IN_PROGRESS);
        $exerciseAttempts = $this->getExerciseAttemptService()->search($data);
        if($exerciseAttempts)
            $this->getExerciseAttemptService()->deleteAttemptAnswersFromQuestion($exerciseAttempts, $question);
        
        $prevQuestionNumber = $this->params()->fromRoute('ref', 0);
        $questionId = $this->getQuestionService()->persistData($question, $image, $prevQuestionNumber);

        if ($questionId > 0) {
            $this->getQuestionService()->clearOptionsFromDb();

            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The question has been successfully saved."));
            $exerciseId = $question->getExerciseId();

            return $this->redirectToIndex($exerciseId, $question->getOrderNumber());

        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The question could not be saved."), "error");
            return $this->redirectToIndex();
        }
    }

    private function displayForm($questionForm, $action = 'add', $imagePath = null)
    {
        
        $viewValues = array(
            'image' => $imagePath,
            'form' => $questionForm,
            'action' => $action
        );

        $view = new ViewModel($viewValues);
        $view->setTemplate('quiz/question/edit');
        return $view;
    }

    /**
     * 
     * @return \Quiz\Service\QuestionService
     */
    protected function getQuestionService()
    {
        return $this->getService('Quiz\Service\QuestionService');
    }
    
    /**
     * 
     * @return \Quiz\Service\ExerciseAttemptService
     */
    private function getExerciseAttemptService()
    {
        return $this->getServiceLocator()->get(
                        'Quiz\Service\ExerciseAttemptService');
    }
    
    private function setBreadcrumbForQuestions($question)
    {
        $exercise = $question->getExercise();
        $this->setBreadcrumbForFeatureActions($exercise->getEquipment(), 'exercise');
        $exerciseViewPage = $this->getNavigationPage('exercise-view');
        if ($exerciseViewPage) {
            $applicationName = $this->params()->fromRoute('application');
            $exerciseViewPage->setParams(
                array(
                    'application' => $applicationName,
                    'exercise' => $question->getExerciseId()
                )
            );
        }
    }
 
    /**
     * 
     * @param type $exerciseId
     * @return type
     */
    private function redirectToIndex($exerciseId = 0, $questionNumber = 1)
    {
        if ($exerciseId > 0) {
            return $this->redirectToPath('exercise', 'detail', 
                    array('quiz' => $exerciseId, 'question' => $questionNumber));
        } else {
            $request = $this->getRequest();
            $httpReferer = $request->getServer('HTTP_REFERER');
            if ($httpReferer) {
                return $this->redirect()->toUrl($httpReferer);
            }
        }
    }
}
