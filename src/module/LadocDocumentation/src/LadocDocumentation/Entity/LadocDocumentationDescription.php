<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_documentation_description")
 * 
 */
class LadocDocumentationDescription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @var string
     * 
     * @ORM\Column(name="lashing_point_description", type="text", nullable=true)
     */
    protected $lashingPointDescription;

    /**
     * @var string
     * 
     * @ORM\Column(name="lifting_point_description", type="text", nullable=true)
     */
    protected $liftingPointDescription;

    /**
     * @var string
     * 
     * @ORM\Column(name="documentation_attachment_description", type="text", nullable=true)
     */
    protected $documentationAttachmentDescription;

    /**
     * @var string
     * 
     * @ORM\Column(name="lashing_equipment_description", type="text", nullable=true)
     */
    protected $lashingEquipmentDescription;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation($ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }

    public function getLashingPointDescription() {
        return $this->lashingPointDescription;
    }

    public function setLashingPointDescription($description) {
        $this->lashingPointDescription = $description;
    }

    public function getLiftingPointDescription() {
        return $this->liftingPointDescription;
    }

    public function setLiftingPointDescription($description) {
        $this->liftingPointDescription = $description;
    }

    public function getDocumentationAttachmentDescription() {
        return $this->documentationAttachmentDescription;
    }

    public function setDocumentationAttachmentDescription($description) {
        $this->documentationAttachmentDescription = $description;
    }

    public function getLashingEquipmentDescription() {
        return $this->lashingEquipmentDescription;
    }

    public function setLashingEquipmentDescription($description) {
        $this->lashingEquipmentDescription = $description;
    }

}