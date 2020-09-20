<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class BasicInformation {

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

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255,  nullable=false)
     */
    protected $approvedName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $colloquialName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mlc;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation($ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }



    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getApprovedName()
    {
        return $this->approvedName;
    }

    public function setApprovedName($approvedName)
    {
        $this->approvedName = $approvedName;
    }

    public function getColloquialName()
    {
        return $this->colloquialName;
    }

    public function setColloquialName($colloquialName)
    {
        $this->colloquialName = $colloquialName;
    }

    public function getMlc()
    {
        return $this->mlc;
    }

    public function setMlc($mlc)
    {
        $this->mlc = $mlc;
    }
}