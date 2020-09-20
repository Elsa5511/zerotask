<?php

namespace LadocDocumentation\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_dimensions")
 */
class CarrierDimensions {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeightAndDimensions", cascade={"persist"}, mappedBy="ownDimensions")
     */
    protected $wd1;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeightAndDimensions", cascade={"persist"}, mappedBy="loadingPlanDimensions")
     */
    protected $wd2;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $length;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $width;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $heightWithNoLoad;

    /**
     * @ORM\OneToMany(targetEntity="CarrierDimensionsAttachment", mappedBy="carrierDimensions", cascade="persist")
     */
    protected $attachments;

    public function __construct() {
        $this->attachments = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getLength() {
        return $this->length;
    }

    public function setLength($length) {
        $this->length = $length;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWidth($width) {
        $this->width = $width;
    }

    public function getHeightWithNoLoad() {
        return $this->heightWithNoLoad;
    }

    public function setHeightWithNoLoad($heightWithNoLoad) {
        $this->heightWithNoLoad = $heightWithNoLoad;
    }

    public function getWd1() {
        return $this->wd1;
    }

    public function setWd1($wd1) {
        $this->wd1 = $wd1;
    }

    public function getWd2() {
        return $this->wd2;
    }

    public function setWd2($wd2) {
        $this->wd2 = $wd2;
    }

    public function getAttachments() {
        return $this->attachments;
    }

    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }

    public function removeAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierDimensions(null);
            $this->attachments->removeElement($attachment);
        }
    }

    public function addAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierDimensions($this);
            $this->attachments->add($attachment);
        }
    }

}