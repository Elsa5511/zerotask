<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;
/**
 * EquipmentInstanceAttachment
 *
 * @ORM\Table(name="equipment_instance_attachment", indexes={@ORM\Index(name="IDX_E6C08F245B13863A", columns={"equipment_instance_id"})})
 * @ORM\Entity
 */
class EquipmentInstanceAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Equipment\Entity\EquipmentInstance
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id")
     * })
     */
    private $equipmentInstance;


 /**
     * Set equipmentInstance
     *
     * @param \Equipment\Entity\EquipmentInstance $equipmentInstance
     * @return EquipmentInstanceAttachment
     */
    public function setEquipmentInstance(\Equipment\Entity\EquipmentInstance $equipmentInstance = null)
    {
        $this->equipmentInstance = $equipmentInstance;
    
        return $this;
    }

    /**
     * Get equipmentInstance
     *
     * @return \Equipment\Entity\EquipmentInstance 
     */
    public function getEquipmentInstance()
    {
        return $this->equipmentInstance;
    }
}
