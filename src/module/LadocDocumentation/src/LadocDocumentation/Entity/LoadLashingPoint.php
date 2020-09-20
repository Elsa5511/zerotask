<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadLashingPoint
 *
 * @ORM\Entity
 * @ORM\Table(name="load_lashing_point")
 */
class LoadLashingPoint extends LashingPoint
{
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LoadLashingPointAttachment", mappedBy="loadLashingPoint", cascade={"all"})
     */
    protected $loadLashingPointAttachments;

    public function __construct() {
        $this->loadLashingPointAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getLoadLashingPointAttachments() {
    	return $this->loadLashingPointAttachments;
    }

    public function getAttachments() {
        return $this->loadLashingPointAttachments;
    }

    public function removeAttachments() {
        $this->loadLashingPointAttachments->clear();
    }

    public function setLoadLashingPointAttachments($attachments) {
        $this->loadLashingPointAttachments = $attachments;
    }

    public function addLoadLashingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadLashingPoint($this);
            $this->loadLashingPointAttachments->add($attachment);
        }
    }

    public function removeLoadLashingPointAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLoadLashingPoint(null);
            $this->loadLashingPointAttachments->removeElement($attachment);
        }
    }
}