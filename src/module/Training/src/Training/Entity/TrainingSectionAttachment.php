<?php

namespace Training\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;
/**
 * TrainingSectionInstanceAttachment
 *
 * @ORM\Table(name="training_section_attachment", indexes={@ORM\Index(name="IDX_E6C08F245B13BW63A", columns={"training_section_id"})})
 * @ORM\Entity
 */
class TrainingSectionAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Training\Entity\TrainingSection
     *
     * @ORM\ManyToOne(targetEntity="Training\Entity\TrainingSection", inversedBy="trainingSectionAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="training_section_id", referencedColumnName="section_id")
     * })
     */
    private $trainingSection;


 /**
     * Set trainingSection
     *
     * @param \Training\Entity\TrainingSection $trainingSection
     * @return TrainingSection
     */
    public function setTrainingSection(\Training\Entity\TrainingSection $trainingSection = null)
    {
        $this->trainingSection = $trainingSection;
    
        return $this;
    }

    /**
     * Get trainingSection
     *
     * @return \Training\Entity\TrainingSection 
     */
    public function getTrainingSection()
    {
        return $this->trainingSection;
    }
}
