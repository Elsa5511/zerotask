<?php

namespace LadocDocumentation\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_weight")
 */
class CarrierWeight {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeightAndDimensions", cascade={"persist"}, mappedBy="ownWeight")
     */
    protected $wd1;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeightAndDimensions", cascade={"persist"}, mappedBy="technicalWeight")
     */
    protected $wd2;


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $frontAxle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $rearAxle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $otherAxles;

    /**
     * @ORM\OneToMany(targetEntity="CarrierWeightAttachment", mappedBy="carrierWeight", cascade="persist")
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

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getFrontAxle() {
        return $this->frontAxle;
    }

    public function setFrontAxle($frontAxle) {
        $this->frontAxle = $frontAxle;
    }

    public function getRearAxle() {
        return $this->rearAxle;
    }

    public function setRearAxle($rearAxle) {
        $this->rearAxle = $rearAxle;
    }

    public function getOtherAxles() {
        return $this->otherAxles;
    }

    public function setOtherAxles($otherAxles) {
        $this->otherAxles = $otherAxles;
    }

    public function getAttachments() {
        return $this->attachments;
    }

    public function setAttachments($attachments) {
        $this->attachments = $attachments;
    }

    public function removeAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierWeight(null);
            $this->attachments->removeElement($attachment);
        }
    }

    public function addAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setCarrierWeight($this);
            $this->attachments->add($attachment);
        }
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


}