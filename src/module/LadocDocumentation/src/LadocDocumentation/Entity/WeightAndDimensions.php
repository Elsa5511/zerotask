<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class WeightAndDimensions {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="LadocDocumentation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_documentation_id", referencedColumnName="id")
     * })
     */
    protected $ladocDocumentation;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return LadocDocumentation
     */
    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation($ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }

    public abstract function loadReferences();
}