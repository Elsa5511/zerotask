<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Quiz;

/**

 * @ORM\Table(name="equipment_exam")
 * @ORM\Entity(repositoryClass="Quiz\Repository\ExamRepository")
 */
class Exam extends Quiz
{

    /**
     * @var integer
     *
     * @ORM\Column(name="time_limit", type="integer", nullable=false)
     * 
     */
    private $timeLimit;

    /**
     * @var \Quiz\Entity\Exercise
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\Exercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="base_on_exercise", referencedColumnName="quiz_id", nullable=false)
     * })
     */
    private $baseOnPracticeExercise;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_questions", type="integer", nullable=false)
     */
    private $numberOfQuestions;
 
    /**
     * Obligatory questions
     * 
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Quiz\Entity\Question")
     * @ORM\JoinTable(name="exam_obligatory_question",
     *   joinColumns={
     *     @ORM\JoinColumn(name="exam_id", referencedColumnName="quiz_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="question_id", referencedColumnName="question_id")
     *   }
     * )
     */
    protected $questions;
    
   
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;
    }

    public function getNumberOfQuestions()
    {
        return $this->numberOfQuestions;
    }

    public function setNumberOfQuestions($numberOfQuestions)
    {
        $this->numberOfQuestions = $numberOfQuestions;
    }
    
    public function getBaseOnPracticeExercise()
    {
        return $this->baseOnPracticeExercise;
    }

    public function setBaseOnPracticeExercise(\Quiz\Entity\Exercise $baseOnPracticeExercise)
    {
        $this->baseOnPracticeExercise = $baseOnPracticeExercise;
    }
    
    /**
     * Add a obligatory question
     * 
     * @param \Quiz\Entity\Question $question
     */
    public function addQuestion(\Quiz\Entity\Question $question)
    {
        $this->questions->add($question);
    }

    /**
     * 
     */
    public function removeQuestions()
    {
        $this->questions->clear();
    }
    
    public function getQuestions()
    {
        return $this->questions;
    }
    
    public function getQuestionsIds()
    {
        $ids = array();
        foreach ($this->questions as $question) {
            if($question->getQuestionId()) {
                $ids[] = $question->getQuestionId();
            }
        }
        return $ids;
    }
    
    public function getExerciseId() {
        if ($this->baseOnPracticeExercise) {
            return $this->baseOnPracticeExercise->getQuizId();
        }
    }
    
    public function __toString()
    {
        $fullname = $this->getEquipment() . " - " . $this->getName();
        return $fullname;
    }

}