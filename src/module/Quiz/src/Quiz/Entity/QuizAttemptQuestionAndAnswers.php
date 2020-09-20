<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quiz
 * @ORM\MappedSuperclass
 */
abstract class QuizAttemptQuestionAndAnswers {

    /**
     * @var integer
     *
     * @ORM\Column(name="quiz_attempt_question_answer_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $quizAttemptQuestionAndAnswerId;

    /**
     * @var Quiz\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="question_id", onDelete="CASCADE")
     * })
     */
    protected $question;

    public function __construct() {
        $this->selectedOptions = new ArrayCollection();
    }

    public function getQuizAttemptQuestionAndAnswerId() {
        return $this->quizAttemptQuestionAndAnswerId;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setQuizAttemptQuestionAndAnswerId($quizAttemptQuestionAndAnswerId) {
        $this->quizAttemptQuestionAndAnswerId = $quizAttemptQuestionAndAnswerId;
    }

    public function setQuestion($question) {
        $this->question = $question;
    }
    
    public abstract function getQuizAttempt();

    public abstract function getSelectedOptions();

    public abstract function setQuizAttempt($quizAttempt);

    public abstract function setSelectedOptions($selectedOptions);

}
