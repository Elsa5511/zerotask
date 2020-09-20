<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Measure
 * @ORM\MappedSuperclass
 */
class Measure {

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    protected $description;

    /**
     * @var string
     * 
     * @ORM\Column(name="quantity", type="string", nullable=true)
     */
    protected $quantity;

    /**
     * @var string
     * 
     * @ORM\Column(name="lc", type="string", nullable=true)
     */
    protected $lc;

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function getLc() {
        return $this->lc;
    }

    public function setLc($lc) {
        $this->lc = $lc;
    }
}