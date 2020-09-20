<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section
 * @ORM\MappedSuperclass
 */
abstract class Section extends \Acl\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="section_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $sectionId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string")
     */
    protected $label;
    
    /**
     * @var int
     *
     * @ORM\Column(name="section_order", type="integer")
     */
    protected $sectionOrder = 0;
    
    public function __toString() {
        return $this->label;
    }
    
    public function getSectionId() {
        return $this->sectionId;
    }

    public function setSectionId($sectionId) {
        $this->sectionId = $sectionId;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function getSectionOrder() {
        return $this->sectionOrder;
    }

    public function setSectionOrder($sectionOrder) {
        $this->sectionOrder = $sectionOrder;
    }

    public function getParent() {
        return $this->parent;
    }

    public function setParent(\Application\Entity\Section $parent = null) {
        $this->parent = $parent;
    }
    
    abstract public function getOwner();
}