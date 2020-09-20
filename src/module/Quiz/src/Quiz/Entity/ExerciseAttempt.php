<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Exercise attempts
 * 
 * @ORM\Table(name="equipment_exercise_attempt")
 * @ORM\Entity(repositoryClass="Quiz\Repository\QuizAttemptRepository")
 */
class ExerciseAttempt extends QuizAttempt {

    /**
     * @var \Quiz\Entity\Exercise
     *
     * @ORM\ManyToOne(targetEntity = "Quiz\Entity\Exercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_id", referencedColumnName="quiz_id", nullable=false)
     * })
     */
    protected $quiz;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "\Quiz\Entity\ExerciseAttemptQuestionAndAnswers", mappedBy="exerciseAttempt", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_attempt_id", referencedColumnName="quiz_attempt_id")
     * })
     */
    protected $questionsAndAnswers;

    public function getQuiz() {
        return $this->quiz;
    }

    public function getQuestionsAndAnswers() {
        return $this->questionsAndAnswers;
    }

    public function setQuiz(\Quiz\Entity\Exercise $exercise) {
        $this->quiz = $exercise;
    }

    public function setQuestionsAndAnswers($questionsAndAnswers) {
        $this->questionsAndAnswers = $questionsAndAnswers;
    }

}
