<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;
use Quiz\Entity\Quiz;

/**
 * @ORM\Table(name="equipment_exercise")
 * @ORM\Entity(repositoryClass="Quiz\Repository\ExerciseRepository")
 */
class Exercise extends Quiz {

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity = "\Quiz\Entity\Question", mappedBy="exercise")
     * @ORM\OrderBy({"orderNumber" = "ASC"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="quiz_id", nullable=false)
     * })
     */
    protected $questions;

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }

    public function hasQuestions() 
    {
        $questionValues = $this->getQuestions()->getValues();
        return (count($questionValues) > 0);        
    }                    
    
    public function __toString() {
        return $this->name;
    }

}
