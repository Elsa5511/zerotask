<?php

namespace Equipment\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_point")
 */
class ControlPoint extends Entity {
    /**
     * @var integer
     *
     * @ORM\Column(name="control_point_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $controlPointId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string")
     */
    protected $label;
    
    /**
     * @ORM\ManyToMany(targetEntity="ControlPointOption")
     * @ORM\JoinTable(name="control_points_control_point_options",
     *      joinColumns={@ORM\JoinColumn(name="control_point_id", referencedColumnName="control_point_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="control_point_option_id", referencedColumnName="control_point_option_id")}
     *      )
     **/
    protected $controlPointOptionCollection;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ControlPointToTemplate", mappedBy="controlPoint")
     **/
    protected $controlPointToTemplate;
    
    public function __construct()
    {
        $this->controlPointOptionCollection = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getId() {
        return $this->controlPointId;
    }
    
    public function getControlPointId() {
        return $this->controlPointId;
    }

    public function setControlPointId($controlPointId) {
        $this->controlPointId = $controlPointId;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }

    public function getControlPointOptionCollection() {
        return $this->controlPointOptionCollection;
    }
    
    public function getControlPointOptionsAsArray() {
        $controlPointOptionsArray = array();
        foreach($this->controlPointOptionCollection as $controlPointOption) {
            $controlPointOptionsArray[$controlPointOption->getControlPointOptionId()] = $controlPointOption->getLabel();
        }
        return $controlPointOptionsArray;
    }

    public function setControlPointOptionCollection($controlPointOptionCollection) {
        $this->controlPointOptionCollection = $controlPointOptionCollection;
    }
    
    public function addControlPointOptionCollection(\Doctrine\Common\Collections\ArrayCollection $controlPointOptionCollection)
    {
        foreach ($controlPointOptionCollection as $controlPointOption) {
            $this->controlPointOptionCollection->add($controlPointOption);
        }
    }

    public function removeControlPointOptionCollection(\Doctrine\Common\Collections\ArrayCollection $controlPointOptionCollection)
    {
        foreach ($controlPointOptionCollection as $controlPointOption) {
            $this->controlPointOptionCollection->removeElement($controlPointOption);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getControlPointToTemplate() {
        return $this->controlPointToTemplate;
    }

    public function setControlPointToTemplate(ControlPointToTemplate $controlPointToTemplate) {
        $this->controlPointToTemplate = $controlPointToTemplate;
    }


    public function __toString() {
        return $this->label;
    }
}