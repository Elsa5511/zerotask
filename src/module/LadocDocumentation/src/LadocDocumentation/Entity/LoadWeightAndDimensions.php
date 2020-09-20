<?php

namespace LadocDocumentation\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("load_weight_and_dimensions")
 */
class LoadWeightAndDimensions extends WeightAndDimensions {

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
    protected $maxHeightWithOwnWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $groundClearanceWithOwnWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ownWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $technicalTotalWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gravityWithOwnWeight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gravityWithTotalWeigth;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gaugeOfWheels;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $overhangAngle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $overhang;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    protected $additionalInfo;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LoadWeightAndDimensionsAttachment", mappedBy="loadWeightAndDimensions", cascade={"all"})
     */
    protected $attachments;

    public function __construct() {
        $this->attachments = new ArrayCollection();
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

    public function getMaxHeightWithOwnWeight() {
        return $this->maxHeightWithOwnWeight;
    }

    public function setMaxHeightWithOwnWeight($maxHeightWithOwnWeight) {
        $this->maxHeightWithOwnWeight = $maxHeightWithOwnWeight;
    }

    public function getGroundClearanceWithOwnWeight() {
        return $this->groundClearanceWithOwnWeight;
    }

    public function setGroundClearanceWithOwnWeight($groundClearanceWithOwnWeight) {
        $this->groundClearanceWithOwnWeight = $groundClearanceWithOwnWeight;
    }

    public function getOwnWeight() {
        return $this->ownWeight;
    }

    public function setOwnWeight($ownWeight) {
        $this->ownWeight = $ownWeight;
    }

    public function getTechnicalTotalWeight() {
        return $this->technicalTotalWeight;
    }

    public function setTechnicalTotalWeight($technicalTotalWeight) {
        $this->technicalTotalWeight = $technicalTotalWeight;
    }

    public function getGravityWithOwnWeight() {
        return $this->gravityWithOwnWeight;
    }

    public function setGravityWithOwnWeight($gravityWithOwnWeight) {
        $this->gravityWithOwnWeight = $gravityWithOwnWeight;
    }

    public function getGravityWithTotalWeigth() {
        return $this->gravityWithTotalWeigth;
    }

    public function setGravityWithTotalWeigth($gravityWithTotalWeigth) {
        $this->gravityWithTotalWeigth = $gravityWithTotalWeigth;
    }

    public function getGaugeOfWheels() {
        return $this->gaugeOfWheels;
    }

    public function setGaugeOfWheels($gaugeOfWheels) {
        $this->gaugeOfWheels = $gaugeOfWheels;
    }

    public function getOverhangAngle() {
        return $this->overhangAngle;
    }

    public function setOverhangAngle($overhangAngle) {
        $this->overhangAngle = $overhangAngle;
    }

    public function getOverhang() {
        return $this->overhang;
    }

    public function setOverhang($overhang) {
        $this->overhang = $overhang;
    }

    public function getAdditionalInfo() {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo($additionalInfo) {
        $this->additionalInfo = $additionalInfo;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAttachments() {
        return $this->attachments;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $attachments
     */
    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }

    public function removeAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadWeightAndDimensions(null);
            $this->attachments->removeElement($attachment);
        }
    }

    public function addAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadWeightAndDimensions($this);
            $this->attachments->add($attachment);
        }
    }

    public function loadReferences() {
        // No references to load.
    }
}