<?php

namespace Quiz\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;
use Application\Service\ServiceOperationException;

abstract class QuizBaseController extends AbstractBaseController
{

    protected abstract function getNewQuiz();

    protected abstract function retrieveQuiz($quizId);

    protected abstract function createQuizForm($formFactory);

    protected abstract function getQuizTypeName();

    protected abstract function getQuizService();

    protected abstract function getViewTemplatePath();
    
    protected abstract function getControllerName();

    public function addAction()
    {
        $request = $this->getRequest();
        
        $quiz = $this->tryToGetNewQuiz();

        if ($quiz == null) {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForFeatureActions($quiz->getEquipment(), $this->getControllerName());
        $quizForm = $this->createAndBindQuizForm($quiz);

        if ($request->isPost()) {
            return $this->storePostData($request->getPost(), $quizForm, $this->getTranslator()->translate("add"));
        } else {
            return $this->displayForm($quizForm);
        }
    }

    protected function tryToGetNewQuiz()
    {
        try {
            return $this->getNewQuiz();
        } catch (ServiceOperationException $exception) {
            $this->sendFlashMessage($exception->getMessage(), 'error');
            return null;
        }
    }

    protected function createAndBindQuizForm($quiz)
    {
        $formFactory = $this->getFormFactory('Quiz');
        $quizForm = $this->createQuizForm($formFactory);
        $quizForm->bind($quiz);
        return $quizForm;
    }

    protected function storePostData($postData, $quizForm, $action)
    {
        $quizForm->setData($postData);
        $quiz = $quizForm->getObject();
        $isFormValid = $quizForm->isValid();

        if ($isFormValid) {
            $this->saveQuizData($quiz);
            return $this->redirectTo('index', array('id' => $quiz->getEquipment()->getEquipmentId()));
        } else {
            return $this->displayForm($quizForm, $action);
        }
    }

    protected function saveQuizData($quiz)
    {
        $quizId = $this->getQuizService()->persistData($quiz);
        $quizType = strtolower($this->getQuizTypeName());

        if ($quizId > 0) {
            $message = sprintf($this->getTranslator()->translate("The %s has been successfully saved."), $quizType);
            $this->sendFlashMessage($message, "success", true);
        } else {
            $message = sprintf($this->getTranslator()->translate("The %s could not be saved. Try again later."), $quizType);
            $this->sendFlashMessage($message, "error");
        }
    }

    protected function redirectTo($action, $params = array())
    {
        return $this->redirectToPath($this->getControllerName(), $action, $params);
    }

    protected function displayForm($quizForm, $action = 'add')
    {
        $viewValues = array(
            'form' => $quizForm,
            'action' => $action
        );

        $view = new ViewModel($viewValues);
        $viewTemplatePath = $this->getViewTemplatePath();
        $view->setTemplate($viewTemplatePath);
        return $view;
    }

    public function editAction()
    {
        $quizId = $this->params()->fromRoute('id', null);
        $equipmentId = $this->params()->fromRoute('equipment_id', null);
        $quiz = $this->retrieveQuiz($quizId);
        if ($quiz) {
            $this->setBreadcrumbForFeatureActions($quiz->getEquipment(), $this->getControllerName());
            $quizForm = $this->createAndBindQuizForm($quiz);
            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->storePostData($request->getPost(), $quizForm, $this->getTranslator()->translate("edit"));
            } else {
                return $this->displayForm($quizForm, 'edit');
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectTo('index', array('id' => $equipmentId));
        }
    }

    public function deleteAction()
    {
        $quizId = $this->params()->fromRoute('id', null);
        $equipmentId = $this->params()->fromRoute('equipment_id', null);
        $quizType = strtolower($this->getQuizTypeName());
        try {
            $this->getQuizService()->deleteById($quizId);
            $message = sprintf($this->getTranslator()->translate("The %s has been deleted successfully."), $quizType);
            $this->sendFlashMessage($message, "success", true);
        } catch (\Application\Service\ServiceOperationException $exception) {
            $this->sendFlashMessage($exception->getMessage(), "error");
        }

        return $this->redirectTo('index', array('id' => $equipmentId));
    }

}