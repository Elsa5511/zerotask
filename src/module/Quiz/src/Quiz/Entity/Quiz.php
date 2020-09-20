<?php

namespace Quiz\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 * @ORM\MappedSuperclass
 */
class Quiz extends \Acl\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="quiz_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $quizId;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var float
     * 
     * @ORM\Column(name="required_for_pass", type="float", nullable=false)
     */
    protected $requiredForPass;

    /**
     * @var string
     * 
     * @ORM\Column(name="introduction_text", type="text", nullable=true)
     */
    protected $introductionText;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id",nullable=false)
     * })
     */
    protected $equipment;

    public function getQuizId() {
        return $this->quizId;
    }

    public function setQuizId($quizId) {
        $this->quizId = $quizId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getRequiredForPass() {
        return $this->requiredForPass;
    }

    public function setRequiredForPass($requiredForPass) {
        $this->requiredForPass = $requiredForPass;
    }

    public function getIntroductionText() {
        return $this->introductionText;
    }

    public function setIntroductionText($introductionText) {
        $this->introductionText = $introductionText;
    }

    public function getEquipment() {
        return $this->equipment;
    }

    public function setEquipment(\Equipment\Entity\Equipment $equipment) {
        $this->equipment = $equipment;
    }

}
