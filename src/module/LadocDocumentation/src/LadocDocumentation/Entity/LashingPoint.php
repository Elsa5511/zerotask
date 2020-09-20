<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LashingPoint
 * @ORM\MappedSuperclass
 */
abstract class LashingPoint extends Point {
	/**
     * @var integer
     *
     * @ORM\Column(name="lashing_point_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $lashingPointId;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation", inversedBy="lashingPoints")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_documentation_id", referencedColumnName="id")
     * })
     */
    protected $ladocDocumentation;

    /**
     * @var string
     *
     * @ORM\Column(name="rupture_strength", type="string", nullable=true)
     */
    protected $ruptureStrength;

    public function getId() {
        return $this->lashingPointId;
    }
    
    public function getLashingPointId() {
        return $this->lashingPointId;
    }

    public function setLashingPointId($lashingPointId) {
        $this->lashingPointId = $lashingPointId;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation($ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }

    public function getRuptureStrength() {
        return $this->ruptureStrength;
    }

    public function setRuptureStrength($ruptureStrength) {
        $this->ruptureStrength = $ruptureStrength;
    }
}