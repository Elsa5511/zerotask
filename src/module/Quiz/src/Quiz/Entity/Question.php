<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**

 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="Quiz\Repository\QuestionRepository")
 */
class Question extends \Acl\Entity\AbstractEntity {

    const TYPE_SINGLE_CHOICE = 'one';
    const TYPE_MULTI_CHOICE = 'many';
    const WEIGHT_DEFAULT = 1;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="question_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $questionId;

    /**
     * @var string
     * 
     * @ORM\Column(name="subject", type="string", nullable=false)
     */
    protected $subject;

    /**
     * @var string
     * 
     * @ORM\Column(name="explanatory_text", type="text", nullable=false)
     */
    protected $explanatoryText;

    /**
     * @var string
     * 
     * @ORM\Column(name="question_text", type="text", nullable=false)
     */
    protected $questionText;

    /**
     * @var string
     * 
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var string
     * 
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    protected $image;

    /**
     * @var string
     * 
     * @ORM\Column(name="resource_link", type="string", nullable=true)
     */
    protected $resourceLink;

    /**
     * @var integer
     * 
     * @ORM\Column(name="weight", type="integer", nullable=true)
     */
    protected $weight = self::WEIGHT_DEFAULT;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="order_number", type="integer", nullable=true)
     */
    protected $orderNumber;

    /**
     * @var \Quiz\Entity\Exercise
     *
     * @ORM\ManyToOne(targetEntity="Quiz\Entity\Exercise", inversedBy="exercise")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exercise_id", referencedColumnName="quiz_id", onDelete="CASCADE")
     * })
     */
    protected $exercise;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Quiz\Entity\Option", mappedBy="question", cascade={"all"})
     */
    protected $options;

    public function __construct() {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getQuestionId() {
        return $this->questionId;
    }

    public function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getExplanatoryText() {
        return $this->explanatoryText;
    }

    public function setExplanatoryText($explanatoryText) {
        $this->explanatoryText = $explanatoryText;
    }

    public function getQuestionText() {
        return $this->questionText;
    }

    public function setQuestionText($questionText) {
        $this->questionText = $questionText;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getResourceLink() {
        return $this->resourceLink;
    }

    public function setResourceLink($resourceLink) {
        $this->resourceLink = $resourceLink;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getOrderNumber()
    {
        return ($this->orderNumber) ? $this->orderNumber : 1;
    }

    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    }
    
    public function getExercise() {
        return $this->exercise;
    }

    public function setExercise(\Quiz\Entity\Exercise $exercise) {
        $this->exercise = $exercise;
    }
    
    public function getExerciseId() {
        if($this->getExercise())
            return $this->getExercise()->getQuizId();
    }

    /**
     * Add an option
     * 
     * @param \Quiz\Entity\Option $option
     */
    public function addOption(\Quiz\Entity\Option $option) {
        $this->options->add($option);
    }

    public function addOptions($options) {
        foreach($options as $option) {
            $option->setQuestion($this);
            $this->options->add($option);
        }
    }

    public function removeOptions($options) {
        foreach($options as $option) {
            $option->setQuestion(null);
            $this->options->removeElement($option);
        }
    }

    public function getOptions() {
        return $this->options;
    }

    public function setOptions($options) {
        $this->options = $options;
    }

}
