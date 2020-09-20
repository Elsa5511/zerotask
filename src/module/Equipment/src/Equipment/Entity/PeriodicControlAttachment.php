<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;
/**
 * EquipmentInstanceAttachment
 *
 * @ORM\Table(name="periodic_control_attachment")
 * @ORM\Entity
 */
class PeriodicControlAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Equipment\Entity\PeriodicControl
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\PeriodicControl")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="periodic_control_id", referencedColumnName="periodic_control_id")
     * })
     */
    protected $periodicControl;


 /**
     * Set periodicControl
     *
     * @param \Equipment\Entity\PeriodicControl $periodicControl
     * @return PeriodicControl
     */
    public function setPeriodicControl(\Equipment\Entity\PeriodicControl $periodicControl = null)
    {
        $this->periodicControl = $periodicControl;
    
        return $this;
    }

    /**
     * Get periodicControl
     *
     * @return \Equipment\Entity\PeriodicControl
     */
    public function getPeriodicControl()
    {
        return $this->periodicControl;
    }

    public function __clone() {
        if ($this->attachmentId) {
            $this->setAttachmentId(null);
        }
    }
}
