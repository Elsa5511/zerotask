<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="equipment_exam_attempt_question_and_answer")
 * @ORM\Entity
 */
class ExamAttemptQuestionAndAnswers extends QuizAttemptQuestionAndAnswers {

    /**
     * @var Quiz\Entity\ExamAttempt
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\ExamAttempt", inversedBy="questionsAndAnswers", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_attempt_id", referencedColumnName="quiz_attempt_id", onDelete="CASCADE")
     * })
     */
    protected $examAttempt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Quiz\Entity\Option")
     * @ORM\JoinTable(name="exam_attempt_answers_to_question_options",
     *      joinColumns={
     *          @ORM\JoinColumn(name="exam_attempt_question_answer_id", referencedColumnName="quiz_attempt_question_answer_id"),
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="option_id", referencedColumnName="option_id")
     *      })
     */
    protected $selectedOptions;

    public function getQuizAttempt() {
        return $this->examAttempt;
    }

    public function getSelectedOptions() {
        return $this->selectedOptions;
    }

    public function setQuizAttempt($examAttempt) {
        $this->examAttempt = $examAttempt;
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
