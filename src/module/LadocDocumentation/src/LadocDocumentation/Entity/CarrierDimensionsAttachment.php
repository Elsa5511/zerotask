<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BasicAttachment;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_dimensions_attachment")
 */
class CarrierDimensionsAttachment extends BasicAttachment {
    /**
     * @ORM\ManyToOne(targetEntity="CarrierDimensions", inversedBy="attachments", cascade="persist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="carrier_dimensions_id", referencedColumnName="id")
     * })
     */
    protected $carrierDimensions;

    public function getCarrierDimensions() {
        return $this->carrierDimensions;
    }

    public function setCarrierDimensions($carrierDimensions) {
        $this->carrierDimensions = $carrierDimensions;
    }

    public function setOwnedBy($carrierDimensions) {
        $this->carrierDimensions = $carrierDimensions;
    }
}