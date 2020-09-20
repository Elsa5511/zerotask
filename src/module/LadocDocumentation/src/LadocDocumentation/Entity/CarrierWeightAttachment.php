<?php

namespace LadocDocumentation\Entity;

use Application\Entity\BasicAttachment;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_weight_attachment")
 */
class CarrierWeightAttachment extends BasicAttachment {

    /**
     * @ORM\ManyToOne(targetEntity="CarrierWeight", inversedBy="attachments", cascade="persist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_weight_id", referencedColumnName="id")
     * })
     */
    protected $carrierWeight;

    public function getCarrierWeight() {
        return $this->carrierWeight;
    }

    public function setCarrierWeight($carrierWeight) {
        $this->carrierWeight = $carrierWeight;
    }

    public function setOwnedBy($carrierWeight) {
        $this->carrierWeight = $carrierWeight;
    }
}