<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="equipment_exercise_attempt_question_and_answer")
 * @ORM\Entity
 */
class ExerciseAttemptQuestionAndAnswers extends QuizAttemptQuestionAndAnswers {

    /**
     * @var Quiz\Entity\ExerciseAttempt
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\ExerciseAttempt", inversedBy="questionsAndAnswers", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_attempt_id", referencedColumnName="quiz_attempt_id", onDelete="CASCADE")
     * })
     */
    protected $exerciseAttempt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Quiz\Entity\Option")
     * @ORM\JoinTable(name="exercise_attempt_answers_to_question_options",
     *      joinColumns={
     *          @ORM\JoinColumn(name="exercise_attempt_question_answer_id", referencedColumnName="quiz_attempt_question_answer_id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="option_id", referencedColumnName="option_id")
     *      })
     */
    protected $selectedOptions;

    public function getQuizAttempt() {
        return $this->exerciseAttempt;
    }

    public function getSelectedOptions() {
        return $this->selectedOptions;
    }

    public function setQuizAttempt($exerciseAttempt) {
        $this->exerciseAttempt = $exerciseAttempt;
    }

    public function setSelectedOptions($selectedOptions) {
        $this->selectedOptions = $selectedOptions;
    }

    public function removeSelectedOptions($selectedOptions) {
        foreach($selectedOptions as $selectedOption) {
            $this->selectedOptions->removeElement($selectedOption);
        }
    }

    public function addSelectedOptions($selectedOptions) {
        foreach($selectedOptions as $selectedOption) {
            $this->selectedOptions->add($selectedOption);
        }
    }

}
