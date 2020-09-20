<?php

namespace Training\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Section;

/**
 * TrainingSection
 *
 * @ORM\Table(name="training_section")
 * @ORM\Entity(repositoryClass="Training\Repository\TrainingSectionRepository")
 */
class TrainingSection extends Section
{

     /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     * })
     */
    private $equipment;

    /**
     * @ORM\OneToMany(targetEntity="Training\Entity\TrainingSectionAttachment", mappedBy="trainingSection")
     */
    private $trainingSectionAttachments;
    
    /**
     * @ORM\ManyToOne(targetEntity="Training\Entity\TrainingSection", inversedBy="children")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="section_id")
     * })
     * @ORM\OrderBy({"sectionOrder" = "ASC"})
     */
    protected $parent;
    
    /**
     * @ORM\OneToMany(targetEntity="Training\Entity\TrainingSection", mappedBy="parent")
     * @ORM\OrderBy({"sectionOrder" = "ASC"})
     */
    protected $children;
    
    /**
     * Set equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return EquipmentAttachment
     */
    public function setEquipment(\Equipment\Entity\Equipment $equipment = null)
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getEquipment()
    {
        return $this->equipment;
    }
    
    public function getTrainingSectionAttachments() {
        return $this->trainingSectionAttachments;
    }
    
    /**
     * Get owner
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getOwner()
    {
        return $this->getEquipment();
    }
    
    public function getChildren() {
        return $this->children;
    }
}

