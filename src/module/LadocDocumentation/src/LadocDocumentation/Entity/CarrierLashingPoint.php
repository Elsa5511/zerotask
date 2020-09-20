<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarrierLashingPoint
 *
 * @ORM\Entity
 * @ORM\Table(name="carrier_lashing_point")
 */
class CarrierLashingPoint extends LashingPoint
{

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CarrierLashingPointAttachment", mappedBy="carrierLashingPoint", cascade={"all"})
     */
    protected $carrierLashingPointAttachments;

    public function __construct() {
        $this->carrierLashingPointAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getCarrierLashingPointAttachments() {
    	return $this->carrierLashingPointAttachments;
    }

    public function getAttachments() {
        return $this->carrierLashingPointAttachments;
    }

    public function removeAttachments() {
        $this->carrierLashingPointAttachments->clear();
    }

    public function setCarrierLashingPointAttachments($attachments) {
        $this->carrierLashingPointAttachments = $attachments;
    }

    public function addCarrierLashingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierLashingPoint($this);
            $this->carrierLashingPointAttachments->add($attachment);
        }
    }

    public function removeCarrierLashingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierLashingPoint(null);
            $this->carrierLashingPointAttachments->removeElement($attachment);
        }
    }
}