<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acl\Entity\AbstractEntity;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;

/**
 *
 * @ORM\Table(name="ladoc_documentation")
 * @ORM\Entity(repositoryClass="LadocDocumentation\Repository\LadocDocumentation")
 */
class LadocDocumentation extends AbstractEntity
{
    const TYPE_LOAD = 'load';
    const TYPE_CARRIER = 'carrier';

    const PAGE_BASIC_INFORMATION = 'basic-information';
    const PAGE_WEIGHT_AND_DIMENSIONS = 'weight-and-dimensions';
    const PAGE_LASHING_POINTS = 'lashing-point';
    const PAGE_LASHING_EQUIPMENT = 'lashing-equipment';
    const PAGE_LIFTING_POINTS = 'lifting-point';
    const PAGE_DOCUMENTATION_ATTACHMENTS = 'ladoc-documentation-attachment';
    const PAGE_END = 'page-end';

    const DIRECTION_NEXT = 'next';
    const DIRECTION_PREVIOUS = 'previous';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\OneToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     */
    protected $equipment;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="finished", type="boolean", nullable=false, options={"default": "0"})
     */
    protected $finished;

    /**
     * @var \LadocDocumentation\Entity\BasicInformation
     */
    protected $basicInformation;

    /**
     * @ORM\OneToOne(targetEntity="LadocDocumentationDescription", mappedBy="ladocDocumentation")
     */
    protected $descriptionInformation;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LoadLashingPoint", mappedBy="ladocDocumentation", cascade={"all"})
     */
    protected $loadLashingPoints;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LoadLiftingPoint", mappedBy="ladocDocumentation", cascade={"all"})
     */
    protected $loadLiftingPoints;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CarrierLashingPoint", mappedBy="ladocDocumentation", cascade={"all"})
     */
    protected $carrierLashingPoints;

    /**
     * @ORM\OneToOne(targetEntity="LoadWeightAndDimensions", mappedBy="ladocDocumentation")
     */
    protected $loadWeightAndDimensions;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeightAndDimensions", mappedBy="ladocDocumentation")
     */
    protected $carrierWeightAndDimensions;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocDocumentationAttachment", mappedBy="ladocDocumentation", cascade={"all"})
     */
    protected $documentationAttachments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="CarrierLashingEquipment", mappedBy="ladocDocumentation", cascade={"all"})
     */
    protected $carrierLashingEquipments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintCertified", mappedBy="loadDocumentation", cascade={"all"})
     */
    protected $loadRestraintCertifieds;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintCertified", mappedBy="carrierDocumentation", cascade={"all"})
     */
    protected $carrierRestraintCertifieds;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintNonCertified", mappedBy="loadDocumentation", cascade={"all"})
     */
    protected $loadRestraintNonCertifieds;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintNonCertified", mappedBy="carrierDocumentation", cascade={"all"})
     */
    protected $carrierRestraintNonCertifieds;

    public function __construct() {
        $this->loadLashingPoints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carrierLashingPoints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->loadLiftingPoints = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentationAttachments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carrierLashingEquipments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->loadRestraintCertifieds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->loadRestraintNonCertifieds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carrierRestraintCertifieds = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carrierRestraintNonCertifieds = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->getEquipment()->getTitle();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Equipment\Entity\Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * @param \Equipment\Entity\Equipment $equipment
     */
    public function setEquipment($equipment)
    {
        $this->equipment = $equipment;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getFinished() {
        return $this->finished;
    }

    /**
     * @param string $type
     */
    public function setFinished($finished) {
        $this->finished = $finished;
    }

    /**
     * @return BasicInformation
     */
    public function getBasicInformation() {
        return $this->basicInformation;
    }

    /**
     * @param BasicInformation $basicInformation
     */
    public function setBasicInformation($basicInformation) {
        $this->basicInformation = $basicInformation;
    }

    /**
     * @return DescriptionInformation
     */
    public function getDescriptionInformation() {
        return $this->descriptionInformation;
    }

    /**
     * @param DescriptionInformation $descriptionInformation
     */
    public function setDescriptionInformation($descriptionInformation) {
        $this->descriptionInformation = $descriptionInformation;
    }




    /**
     * @return bool
     */
    public function isComplete() {
        return $this->getFinished();
    }

    /**
     * @return bool
     */
    public function hasBasicInformation()
    {
        return !is_null($this->getBasicInformation());
    }

    /**
     * @return bool
     */
    public function hasDescriptionInformation()
    {
        return !is_null($this->getDescriptionInformation());
    }

    /**
     * @return bool
     */
    public function hasLashingPoints()
    {
        return $this->getLashingPoints()->count() > 0;
    }

    public function getLashingPoints() {
        if($this->getType() == LadocDocumentation::TYPE_LOAD)
            return $this->loadLashingPoints;
        else
            return $this->carrierLashingPoints;
    }

    public function setLashingPoints($points) {
        if($this->getType() == LadocDocumentation::TYPE_LOAD)
            $this->loadLashingPoints = $points;
        else
            $this->carrierLashingPoints = $points;
    }

    /**
     * @return bool
     */
    public function hasLiftingPoints()
    {
        return $this->getLiftingPoints()->count() > 0;
    }

    public function getLiftingPoints() {
        return $this->loadLiftingPoints;
    }

    public function setLiftingPoints($points) {
        $this->loadLiftingPoints = $points;
    }

    /**
     * @return bool
     */
    public function hasWeightAndDimensions()
    {
        return !is_null($this->getWeightAndDimensions());
    }

    public function getWeightAndDimensions() {
        if ($this->getType() === LadocDocumentation::TYPE_LOAD) {
            return $this->loadWeightAndDimensions;
        }
        else if ($this->getType() === LadocDocumentation::TYPE_CARRIER) {
            return $this->carrierWeightAndDimensions;
        }
        else {
            return null;
        }
    }

    public function hasDocumentationAttachments()
    {
        return $this->getDocumentationAttachments()->count() > 0;
    }

    public function getDocumentationAttachments() {
        return $this->documentationAttachments;
    }

    public function setDocumentationAttachments($attachments) {
        $this->documentationAttachments = $attachments;
    }

    /**
     * @return bool
     */
    public function hasLashingEquipments()
    {
        return $this->getLashingEquipments()->count() > 0;
    }

    public function getLashingEquipments() {
        return $this->carrierLashingEquipments;
    }

    public function setLashingEquipments($lashingEquipments) {
        $this->carrierLashingEquipments = $lashingEquipments;
    }

    public function getLoadRestraintCertifieds() {
        return $this->loadRestraintCertifieds;
    }

    public function setLoadRestraintCertifieds($restraintCertifieds) {
        $this->loadRestraintCertifieds = $restraintCertifieds;
    }

    public function getCarrierRestraintCertifieds() {
        return $this->carrierRestraintCertifieds;
    }

    public function setCarrierRestraintCertifieds($restraintCertifieds) {
        $this->carrierRestraintCertifieds = $restraintCertifieds;
    }

    public function getLoadRestraintNonCertifieds() {
        return $this->loadRestraintNonCertifieds;
    }

    public function setLoadRestraintNonCertifieds($restraintNonCertifieds) {
        $this->loadRestraintNonCertifieds = $restraintNonCertifieds;
    }

    public function getCarrierRestraintNonCertifieds() {
        return $this->carrierRestraintNonCertifieds;
    }

    public function setCarrierRestraintNonCertifieds($restraintNonCertifieds) {
        $this->carrierRestraintNonCertifieds = $restraintNonCertifieds;
    }

    public function getLowestTaxonomyTemplateType() {
        $templateType = null;
        $taxonomy = $this->getEquipment()->getFirstEquipmentTaxonomy();
        while($taxonomy) {
            $templateType = $taxonomy->getTemplateType();
            if($templateType)   break;

            $taxonomy = $taxonomy->getParent();
        }

        return $templateType ? $templateType : EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD;
    }
}

