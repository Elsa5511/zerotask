<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadLiftingPoint
 *
 * @ORM\Entity
 * @ORM\Table(name="load_lifting_point")
 */
class LoadLiftingPoint extends Point {
	/**
     * @var integer
     *
     * @ORM\Column(name="lifting_point_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $liftingPointId;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation", inversedBy="liftingPoints")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_documentation_id", referencedColumnName="id")
     * })
     */
    protected $ladocDocumentation;

    /**
     * @var string
     * 
     * @ORM\Column(name="rupture_strength", type="string", nullable=false)
     */
    protected $ruptureStrength;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LoadLiftingPointAttachment", mappedBy="loadLiftingPoint", cascade={"all"})
     */
    protected $loadLiftingPointAttachments;

    public function __construct() {
        $this->loadLiftingPointAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->liftingPointId;
    }

    public function getLiftingPointId() {
        return $this->liftingPointId;
    }

    public function setLiftingPointId($liftingPointId) {
        $this->liftingPointId = $liftingPointId;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation(LadocDocumentation $ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }

    public function getRuptureStrength() {
        return $this->ruptureStrength;
    }

    public function setRuptureStrength($ruptureStrength) {
        $this->ruptureStrength = $ruptureStrength;
    }

    public function getLoadLiftingPointAttachments() {
        return $this->loadLiftingPointAttachments;
    }

    public function getAttachments() {
        return $this->loadLiftingPointAttachments;
    }

    public function removeAttachments() {
        $this->loadLiftingPointAttachments->clear();
    }

    public function setLoadLiftingPointAttachments($attachments) {
        $this->loadLiftingPointAttachments = $attachments;
    }

    public function addLoadLiftingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadLiftingPoint($this);
            $this->loadLiftingPointAttachments->add($attachment);
        }
    }

    public function removeLoadLiftingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadLiftingPoint(null);
            $this->loadLiftingPointAttachments->removeElement($attachment);
        }
    }
}