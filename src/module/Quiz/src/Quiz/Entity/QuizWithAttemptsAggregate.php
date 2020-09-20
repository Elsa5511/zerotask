<?php

namespace Quiz\Entity;

/**
 * All classes in the Entity directory need to import
 * Mapping, even when the class is not mapped to a table.
 */
use Doctrine\ORM\Mapping as ORM;

class QuizWithAttemptsAggregate {
    protected $quizId;
    protected $name;
    protected $lastAttempt;
    protected $bestScore;
    protected $exerciseStatus;
    protected $status;

    public function __construct($quizId, $name, $lastAttempt, $bestResult, $status) {
        $this->quizId = $quizId;
        $this->name = $name;
        $this->lastAttempt = $lastAttempt;
        $this->bestScore = $bestResult;
        $this->status = $status;
    }

    public function getQuizId() {
        return $this->quizId;
    }

    public function getName() {
        return $this->name;
    }

    public function getLastAttempt() {
        return $this->lastAttempt;
    }

    public function getBestResult() {
        return $this->bestScore;
    }
    
    public function getStatus() {
        return $this->status;
    }    
}
