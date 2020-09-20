<?php

namespace Quiz\Form;

use Application\Form\AbstractFormFactory;

class FormFactory extends AbstractFormFactory {

    public function createExerciseForm() {
        $exerciseFieldset = $this->getExerciseFieldset();
        $exerciseForm = $this->getNewForm('exercise');
        $exerciseForm->add($exerciseFieldset);
        $exerciseForm->add($this->getSaveButton());

        return $exerciseForm;
    }

    private function getExerciseFieldset() {
        $exerciseFieldset = new QuizFieldset('exercise');
        $this->setupFieldset($exerciseFieldset, 'Quiz\Entity\Exercise');
        return $exerciseFieldset;
    }

    /**
     * Creates an exercise search form
     * Returns an instance of Form
     * 
     */
    public function createExerciseSearchForm() {
        $entityParams = array(
            "data-placeholder" => $this->getTranslator()->translate("Choose an exercise"),
            "target_class" => "Quiz\Entity\Exercise",
        );
        return $this->getQuizSearchForm($entityParams);
    }

    /**
     * Creates an exam search form
     * Returns an instance of Form
     * 
     */
    public function createExamSearchForm() {
        $entityParams = array(
            "data-placeholder" => $this->getTranslator()->translate("Choose an exam"),
            "target_class" => "Quiz\Entity\Exam",
        );
        return $this->getQuizSearchForm($entityParams);
    }

    private function getQuizSearchForm($entityParams) {
        $searchFieldset = new QuizSearchFieldset(
                $this->getObjectManager(), $entityParams, $this->getTranslator());
        $form = $this->getNewForm('quiz-search');
        $form->add($searchFieldset);
        return $form;
    }

    public function createExamForm($equipmentId) {
        $examFieldset = $this->getExamFieldset($equipmentId);
        $examForm = $this->getNewForm('exam');
        $examForm->add($examFieldset);
        $examForm->add($this->getSaveButton());

        return $examForm;
    }

    private function getExamFieldset($equipmentId) {
        $examFieldset = new ExamFieldset($this->getObjectManager(), $equipmentId, $this->getTranslator());
        $this->setupFieldset($examFieldset, 'Quiz\Entity\Exam');
        return $examFieldset;
    }

    public function createQuestionForm() {
        $questionFieldset = $this->getQuestionFieldset();
        $questionForm = $this->getNewForm('question');
        $questionForm->add($questionFieldset);

        return $questionForm;
    }

    private function getQuestionFieldset() {
        $questionFieldset = new QuestionFieldset($this->getObjectManager(), $this->getTranslator());
        $this->setupFieldset($questionFieldset, 'Quiz\Entity\Question');
        return $questionFieldset;
    }

    public function createExamAttemptForm($application) {
        $attemptFieldset = new ExamAttemptFieldset($this->getObjectManager(), $application);
        $this->setupFieldset($attemptFieldset, 'Quiz\Entity\ExamAttempt');
        $attemptForm = $this->getNewForm('exam-attempt');
        $attemptForm->add($attemptFieldset);
        return $attemptForm;
    }

    private function getSaveButton() {
        return array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->getTranslator()->translate('Save')
            ),
        );
    }

}
