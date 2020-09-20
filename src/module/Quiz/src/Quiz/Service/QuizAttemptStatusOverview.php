<?php

namespace Quiz\Service;

use Quiz\Entity\QuizAttempt;

class QuizAttemptStatusOverview {

    private $totalNoOfQuestions = 0;
    private $questionsAnswered = 0;
    private $correctAnswers = 0;
    private $totalResult = 0;
    private $totalPossibleResult = 0;
    private $requiredScorePercentage = 0;

    public function getTotalNoOfQuestions() {
        return $this->totalNoOfQuestions;
    }

    public function getQuestionsAnswered() {
        return $this->questionsAnswered;
    }

    public function getCorrectAnswers() {
        return $this->correctAnswers;
    }

    public function getTotalResult() {
        return $this->totalResult;
    }

    public function getTotalPossibleResult() {
        return $this->totalPossibleResult;
    }

    public function getRequiredScorePercentage() {
        return $this->requiredScorePercentage;
    }

    public function setTotalNoOfQuestions($totalNoOfQuestions) {
        $this->totalNoOfQuestions = $totalNoOfQuestions;
    }

    public function setQuestionsAnswered($questionsAnswered) {
        $this->questionsAnswered = $questionsAnswered;
    }

    public function setCorrectAnswers($correctAnswers) {
        $this->correctAnswers = $correctAnswers;
    }

    public function setTotalResult($totalResult) {
        $this->totalResult = $totalResult;
    }

    public function setTotalPossibleResult($totalPossibleResult) {
        $this->totalPossibleResult = $totalPossibleResult;
    }

    public function setRequiredScorePercentage($requiredScorePercentage) {
        $this->requiredScorePercentage = $requiredScorePercentage;
    }

    public function getResultAsPercentage() {
        return ($this->getTotalResult() / $this->getTotalPossibleResult()) * 100;
    }

    public function getCurrentStatus() {
        if ($this->getResultAsPercentage() >= $this->getRequiredScorePercentage()) {
            return QuizAttempt::STATUS_PASSED;
        } else {
            return QuizAttempt::STATUS_FAILED;
        }
    }

}
