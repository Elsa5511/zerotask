<?php

namespace Quiz\Service;

use Application\Service\AbstractBaseService;
use Quiz\Entity\QuizAttempt;

abstract class QuizAttemptService extends AbstractBaseService {

    public function createStatusOverview($quizAttempt) {
        $statusOverviewCalculator = new QuizAttemptStatusOverviewCalculator();
        return $statusOverviewCalculator->createStatusOverview($quizAttempt);
    }

    public function answersAreCorrect($questionAndAnswers) {
        $quizAnswerValidator = new QuizAnswerValidator();
        return $quizAnswerValidator->answersAreCorrect($questionAndAnswers);
    }

    /**
     * 
     * @param type $questionsAndAnswers
     * @return array
     */
    public function getAnswerStatuses($questionsAndAnswers, $answerValidator) {
        $answerStatuses = array();
        foreach ($questionsAndAnswers as $questionAndAnswers) {
            $status = $this->getAnswerStatus($questionAndAnswers, $answerValidator);
            array_push($answerStatuses, $status);
        }
        return $answerStatuses;
    }

    public function getAnswerStatus($questionAndAnswers, $answerValidator) {
        if ($questionAndAnswers) {
            if (count($questionAndAnswers->getSelectedOptions()) == 0) {
                $status = '';
            } else if ($answerValidator->answersAreCorrect($questionAndAnswers)) {
                $status = 'correct';
            } else {
                $status = 'incorrect';
            }
        } else {
            $status = '';
        }
        return $status;
    }

    public function completeAttempt($exerciseAttempt) {
        $statusOverview = $this->createStatusOverview($exerciseAttempt);
        if ($statusOverview->getCurrentStatus() === QuizAttempt::STATUS_PASSED) {
            $exerciseAttempt->setStatusPassed();
        } else {
            $exerciseAttempt->setStatusFailed();
        }
        $exerciseAttempt->setResult($statusOverview->getResultAsPercentage());

        $exerciseAttempt->getQuestionsAndAnswers()->clear();
        $this->persist($exerciseAttempt);
    }
    
    public function deleteAttemptAnswersFromQuestion($exerciseAttempts, $question){
        $executeFlush = false;
        foreach($exerciseAttempts as $exerciseAttempt){
            foreach($exerciseAttempt->getQuestionsAndAnswers() as $questionAndAnswer){
                if($questionAndAnswer->getQuestion()->getQuestionId() == $question->getQuestionId()){
                    $questionAndAnswer->setSelectedOptions(null);
                    $executeFlush = true;
                }
            }
        }
        if($executeFlush)
            $this->getEntityManager()->flush();
    }

}
