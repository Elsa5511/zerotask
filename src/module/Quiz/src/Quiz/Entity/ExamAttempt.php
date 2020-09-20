<?php

namespace Quiz\Entity;

use DateInterval;
use Doctrine\ORM\Mapping as ORM;

/**
 * Exam attempts
 * 
 * @ORM\Table(name="equipment_exam_attempt")
 * @ORM\Entity(repositoryClass="Quiz\Repository\QuizAttemptRepository")
 */
class ExamAttempt extends QuizAttempt {

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expirationDate;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    protected $startTime;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    protected $endTime;

    /**
     * @var \Quiz\Entity\Exam
     *
     * @ORM\ManyToOne(targetEntity = "Quiz\Entity\Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="quiz_id", nullable=false)
     * })
     */
    protected $quiz;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "\Quiz\Entity\ExamAttemptQuestionAndAnswers", mappedBy="examAttempt", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_attempt_id", referencedColumnName="exam_attempt_id")
     * })
     */
    protected $questionsAndAnswers;
    protected $toStart;
    protected $toContinue;

    public function getTimeUsed() {
        if ($this->startTime && $this->endTime) {
            $timeDiffInSeconds = $this->startTime->format('U') - $this->getEndTime()->format('U');
            $timeDiffInMinutes = (int) round(abs($timeDiffInSeconds) / 60, 2);
            if ($timeDiffInMinutes == 0) {
                $timeDiffInMinutes = 1;
            }
            return $timeDiffInMinutes;
        }
        else {
            return null;
        }
    }

    /**
     * 
     * @return \Quiz\Entity\Exam
     */
    public function getQuiz() {
        return $this->quiz;
    }

    public function setQuiz(\Quiz\Entity\Exam $exam) {
        $this->quiz = $exam;
    }

    public function getQuestionsAndAnswers() {
        return $this->questionsAndAnswers;
    }

    public function setQuestionsAndAnswers($questionsAndAnswers) {
        $this->questionsAndAnswers = $questionsAndAnswers;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate() {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     */
    public function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;
    }

    public function getStartTime() {
        return $this->startTime;
    }

    public function getEndTime() {
        return $this->endTime;
    }

    public function setStartTime(\DateTime $startTime) {
        $this->startTime = $startTime;
    }

    public function setEndTime(\DateTime $endTime) {
        $this->endTime = $endTime;
    }

    public function isInProgress() {
        return ($this->getStatus() === self::STATUS_IN_PROGRESS);
    }

    public function isAbleToStart() {
        return ($this->getStatus() === self::STATUS_NOT_STARTED);
    }

    public function getToStart() {
        if ($this->isAbleToStart()) {
            $this->toStart = $this->getAttemptId();
        } else {
            $this->toStart = 0;
        }
        return $this->toStart;
    }

    public function getToContinue() {
        if ($this->isInProgress()) {
            $this->toContinue = $this->getAttemptId();
        } else {
            $this->toContinue = 0;
        }
        return $this->toContinue;
    }

    public function isExpired() {
        if ($this->expirationDate) {
            $now = new \DateTime();
            $now->setTime(0, 0, 0);
            return ($this->expirationDate < $now);
        }
        else {
            return false;
        }
    }

}
