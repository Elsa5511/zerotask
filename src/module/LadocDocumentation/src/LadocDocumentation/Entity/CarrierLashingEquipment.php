<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarrierLashingEquipment
 *
 * @ORM\Entity
 * @ORM\Table(name="carrier_lashing_equipment")
 */
class CarrierLashingEquipment extends Measure {
	/**
     * @var integer
     *
     * @ORM\Column(name="lashing_equipment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $lashingEquipmentId;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_documentation_id", referencedColumnName="id")
     * })
     */
    protected $ladocDocumentation;

    /**
     * @var string
     * 
     * @ORM\Column(name="nsn", type="string", nullable=false)
     */
    protected $nsn;

    /**
     * @var string
     * 
     * @ORM\Column(name="length", type="string", nullable=false)
     */
    protected $length;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CarrierLashingEquipmentAttachment", mappedBy="carrierLashingEquipment", cascade={"all"})
     */
    protected $carrierLashingEquipmentAttachments;

    public function __construct() {
        $this->carrierLashingEquipmentAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->lashingEquipmentId;
    }

    public function getLashingEquipmentId() {
        return $this->lashingEquipmentId;
    }

    public function setLashingEquipmentId($lashingEquipmentId) {
        $this->lashingEquipmentId = $lashingEquipmentId;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation(LadocDocumentation $ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }

    public function getNsn() {
        return $this->nsn;
    }

    public function setNsn($nsn) {
        $this->nsn = $nsn;
    }

    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function getCarrierLashingEquipmentAttachments() {
        return $this->carrierLashingEquipmentAttachments;
    }

    public function getAttachments() {
        return $this->carrierLashingEquipmentAttachments;
    }

    public function removeAttachments() {
        $this->carrierLashingEquipmentAttachments->clear();
    }

    public function removeCarrierLashingEquipmentAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierLashingEquipment(null);
            $this->carrierLashingEquipmentAttachments->removeElement($attachment);
        }
    }

    public function addCarrierLashingEquipmentAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierLashingEquipment($this);
            $this->carrierLashingEquipmentAttachments->add($attachment);
        }
    }

    public function setCarrierLashingEquipmentAttachments($attachments) {
        $this->carrierLashingEquipmentAttachments = $attachments;
    }
}