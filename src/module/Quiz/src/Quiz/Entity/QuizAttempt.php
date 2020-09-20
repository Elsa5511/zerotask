<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quiz
 * @ORM\MappedSuperclass
 */
abstract class QuizAttempt extends \Acl\Entity\AbstractEntity {

    const STATUS_NOT_STARTED = 'not-started';
    const STATUS_IN_PROGRESS = 'in-progress';
    const STATUS_FAILED = 'failed';
    const STATUS_PASSED = 'passed';

    /**
     * @var integer
     *
     * @ORM\Column(name="quiz_attempt_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $attemptId;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="attempt_date", type="datetime", nullable=true)
     */
    protected $attemptDate;

    /**
     * @var float
     * 
     * @ORM\Column(name="result", type="float", nullable=true)
     */
    protected $result;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=false)
     */
    protected $status = self::STATUS_NOT_STARTED;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity = "Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $user;

    public function __construct() {
        $this->questionsAndAnswers = new ArrayCollection();
    }

    public function getAttemptId() {
        return $this->attemptId;
    }

    public function setAttemptId($attemptId) {
        $this->attemptId = $attemptId;
    }

    public function getAttemptDate() {
        return $this->attemptDate;
    }

    public function setAttemptDate(\DateTime $attemptDate) {
        $this->attemptDate = $attemptDate;
    }

    public function getResult() {
        return ($this->result !== null) ? number_format($this->result, 1) . ' %' : '';
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(\Application\Entity\User $user) {
        $this->user = $user;
    }

    public function getEquipment() {
        $quiz = $this->getQuiz();
        if ($quiz) {
            return $quiz->getEquipment();
        } else {
            return "";
        }
    }

    public function setStatusPassed() {
        $this->setStatus(self::STATUS_PASSED);
    }

    public function setStatusFailed() {
        $this->setStatus(self::STATUS_FAILED);
    }

    public abstract function getQuiz();

    public abstract function getQuestionsAndAnswers();

    public abstract function setQuestionsAndAnswers($questionsAndAnswers);
    
    public function __toString()
    {
        $string = $this->getQuiz()->getName();
        if($this->getUser()) {
            $string .= " - " . $this->getUser()->getDisplayName();
        }
        return $string;
    }
    
    public function getStatusForPrint() {
        if ($this->status === self::STATUS_NOT_STARTED) {
            return 'Not started';
        }
        else if ($this->status === self::STATUS_IN_PROGRESS) {
            return 'In progress';
        }
        else if ($this->status === self::STATUS_FAILED) {
            return 'Failed';
        }
        if ($this->status === self::STATUS_PASSED) {
            return 'Passed';
        }
    }
}
