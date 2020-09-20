<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**

 * @ORM\Table(name="question_option")
 * @ORM\Entity
 */
class Option
{

    /**
     * @var integer
     *
     * @ORM\Column(name="option_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $optionId;

    /**
     * @var string
     * 
     * @ORM\Column(name="option_text", type="string", length=500, nullable=true)
     */
    protected $optionText;
    /**
     * @var boolean
     * 
     * @ORM\Column(name="isCorrect", type="boolean", nullable=true)
     */
    protected $isCorrect;

     /**
     * @var \Quiz\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\Question", inversedBy="options", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="question_id", referencedColumnName="question_id", onDelete="CASCADE")
     * })
     */
    protected $question;


    public function getOptionId()
    {
        return $this->optionId;
    }

    public function setOptionId($optionId)
    {
        $this->optionId = $optionId;
    }

    public function getOptionText()
    {
        return $this->optionText;
    }

    public function setOptionText($optionText)
    {
        $this->optionText = $optionText;
    }

    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    public function setIsCorrect($answer)
    {
        $this->isCorrect = $answer;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }
    
    public function isCorrect() {
        return $this->getIsCorrect();
    }

}