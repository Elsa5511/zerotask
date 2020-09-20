<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Point
 * @ORM\MappedSuperclass
 */
class Point extends Measure {
	/**
     * @var string
     * 
     * @ORM\Column(name="placement", type="string", nullable=false)
     */
    protected $placement;


    public function __toString() {
        return $this->placement;
    }

    public function getPlacement() {
        return $this->placement;
    }

    public function setPlacement($placement) {
        $this->placement = $placement;
    }
}