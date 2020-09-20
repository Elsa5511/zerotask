<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarrierLashingPointAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="carrier_lashing_point_attachment")
 */
class CarrierLashingPointAttachment extends PointAttachment
{
     /**
     * @var CarrierLashingPoint
     *
     * @ORM\ManyToOne(targetEntity="CarrierLashingPoint", inversedBy="carrierLashingPointAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_lashing_point_id", referencedColumnName="lashing_point_id")
     * })
     */
    private $carrierLashingPoint;

    /**
     * Set carrier lashing point
     *
     * @param CarrierLashingPoint $carrierLashingPoint
     * @return CarrierLashingPointAttachment
     */
    public function setCarrierLashingPoint(CarrierLashingPoint $carrierLashingPoint = null)
    {
        $this->carrierLashingPoint = $carrierLashingPoint;

        return $this;
    }

    /**
     * Get carrier lashing point
     *
     * @return CarrierLashingPoint
     */
    public function getCarrierLashingPoint()
    {
        return $this->carrierLashingPoint;
    }

}
