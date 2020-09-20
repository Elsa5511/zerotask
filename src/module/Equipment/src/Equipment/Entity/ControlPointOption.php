<?php

namespace Equipment\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_point_option")
 */
class ControlPointOption extends Entity {
    /**
     * @var integer
     *
     * @ORM\Column(name="control_point_option_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $controlPointOptionId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string")
     */
    protected $label;
    
    public function getId() {
        return $this->controlPointOptionId;
    }
    
    public function getControlPointOptionId() {
        return $this->controlPointOptionId;
    }

    public function setControlPointOptionId($controlPointOptionId) {
        $this->controlPointOptionId = $controlPointOptionId;
    }

    public function getLabel() {
        return $this->label;
    }

    public function setLabel($label) {
        $this->label = $label;
    }
    
    public function __toString() {
        return $this->label;
    }

}
