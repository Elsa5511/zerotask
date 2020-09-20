<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarrierLashingEquipmentAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="carrier_lashing_equipment_attachment")
 */
class CarrierLashingEquipmentAttachment extends PointAttachment
{
     /**
     * @var CarrierLashingEquipment
     *
     * @ORM\ManyToOne(targetEntity="CarrierLashingEquipment", inversedBy="carrierLashingEquipmentAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_lashing_equipment_id", referencedColumnName="lashing_equipment_id")
     * })
     */
    private $carrierLashingEquipment;

    /**
     * Set carrier lashing equipment
     *
     * @param CarrierLashingEquipment $carrierLashingEquipment
     * @return CarrierLashingEquipmentAttachment
     */
    public function setCarrierLashingEquipment(CarrierLashingEquipment $carrierLashingEquipment = null)
    {
        $this->carrierLashingEquipment = $carrierLashingEquipment;

        return $this;
    }

    /**
     * Get carrier lashing equipment
     *
     * @return CarrierLashingEquipment
     */
    public function getCarrierLashingEquipment()
    {
        return $this->carrierLashingEquipment;
    }

}
