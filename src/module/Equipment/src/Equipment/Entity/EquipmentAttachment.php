<?php

namespace Equipment\Entity;

use Application\Entity\AttachmentWithLink;
use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Attachment;

/**
 * EquipmentAttachment
 *
 * @ORM\Table(name="equipment_attachment", indexes={@ORM\Index(name="IDX_50542D65517FE9FE", columns={"equipment_id"})})
 * @ORM\Entity
 */
class EquipmentAttachment extends AttachmentWithLink
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

}
