<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LadocRestraintCertified
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_restraint_certified")
 */
class LadocRestraintCertified {
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation\Entity\LadocDocumentation", inversedBy="loadRestraintCertifieds")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="load_documentation_id", referencedColumnName="id")
     * })
     */
    protected $loadDocumentation;

    /**
     * @var \LadocDocumentation\Entity\LadocDocumentation
     *
     * @ORM\ManyToOne(targetEntity="LadocDocumentation\Entity\LadocDocumentation", inversedBy="carrierRestraintCertifieds")
     * @ORM\JoinColumn(name="carrier_documentation_id", referencedColumnName="id")
     */
    protected $carrierDocumentation;

    /**
     * @var string
     *
     * @ORM\Column(name="illustration_image", type="string", nullable=true)
     */
    protected $illustrationImage;

    /**
     * @var string
     * 
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    protected $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_information", type="string", nullable=true)
     */
    protected $imageInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="calculation_information", type="string", nullable=true)
     */
    protected $calculationInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="railway_certificate", type="string", nullable=true)
     */
    protected $railwayCertificate;

    /**
     * @var string
     *
     * @ORM\Column(name="railway_calculation", type="string", nullable=true)
     */
    protected $railwayCalculation;

    /**
     * @var string
     *
     * @ORM\Column(name="railway_tunell_profile", type="string", nullable=true)
     */
    protected $railwayTunellProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="attla", type="string", nullable=true)
     */
    protected $attla;

    /**
     * @var string
     *
     * @ORM\Column(name="control_list", type="string", nullable=true)
     */
    protected $controlList;

    /**
     * @var string
     * 
     * @ORM\Column(name="other_loads", type="string", nullable=true)
     */
    protected $otherLoads;

    /**
     * @ORM\ManyToMany(targetEntity="FormOfTransportation")
     * @ORM\JoinTable(name="ladoc_restraint_certified_to_form_of_transportation",
     *      joinColumns = {
     *          @ORM\JoinColumn(name="ladoc_restraint_certified_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns = {
     *          @ORM\JoinColumn(name="form_of_transportation_id", referencedColumnName="id")
     *      })
     */
    protected $approvedFormsOfTransportation;

    /**
     * @var string
     * 
     * @ORM\Column(name="created_by", type="string", nullable=false)
     */
    protected $createdBy;

    /**
     * @var string
     * 
     * @ORM\Column(name="approved_by", type="string", nullable=false)
     */
    protected $approvedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date", type="datetime", nullable=true)
     */
    protected $approvedDate;

    /**
     * @var string
     * 
     * @ORM\Column(name="prerequisites", type="text", nullable=true)
     */
    protected $prerequisites;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintCertifiedAttachment", mappedBy="ladocRestraintCertified", cascade={"all"})
     */
    protected $ladocRestraintCertifiedAttachments;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="LadocRestraintCertifiedDocument", mappedBy="ladocRestraintCertified", cascade={"all"})
     */
    protected $ladocRestraintCertifiedDocuments;

    public function __construct() {
        $this->approvedFormsOfTransportation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ladocRestraintCertifiedAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getLoadDocumentation() {
        return $this->loadDocumentation;
    }

    public function setLoadDocumentation($loadDocumentation) {
        $this->loadDocumentation = $loadDocumentation;
    }

    public function getCarrierDocumentation () {
        return $this->carrierDocumentation;
    }

    public function setCarrierDocumentation ($carrierDocumentation) {
        $this->carrierDocumentation = $carrierDocumentation;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getImageInformation() {
        return $this->imageInformation;
    }

    public function setImageInformation($imageInformation) {
        $this->imageInformation = $imageInformation;
    }

    public function getCalculationInformation() {
        return $this->calculationInformation;
    }

    public function setCalculationInformation($calculationInformation) {
        $this->calculationInformation = $calculationInformation;
    }

    public function getRailwayCertificate() {
        return $this->railwayCertificate;
    }

    public function setRailwayCertificate ($railwayCertificate) {
        $this->railwayCertificate = $railwayCertificate;
    }

    public function getRailwayCalculation () {
        return $this->railwayCalculation;
    }

    public function setRailwayCalculation ($railwayCalculation) {
        $this->railwayCalculation = $railwayCalculation;
    }

    public function getRailwayTunellProfile () {
        return $this->railwayTunellProfile;
    }

    public function setRailwayTunellProfile ($railwayTunellProfile) {
        $this->railwayTunellProfile = $railwayTunellProfile;
    }

    public function getAttla() {
        return $this->attla;
    }

    public function setAttla($attla) {
        $this->attla = $attla;
    }

    public function getControlList() {
        return $this->controlList;
    }

    public function setControlList($controlList) {
        $this->controlList = $controlList;
    }

    /**
     * @return string
     */
    public function getIllustrationImage() {
        return $this->illustrationImage;
    }

    /**
     * @param string $illustrationImage
     */
    public function setIllustrationImage($illustrationImage) {
        $this->illustrationImage = $illustrationImage;
    }

    public function getOtherLoads() {
        return $this->otherLoads;
    }

    public function setOtherLoads($otherLoads) {
        $this->otherLoads = $otherLoads;
    }

    public function getApprovedFormsOfTransportation () {
        return $this->approvedFormsOfTransportation;
    }

    public function setApprovedFormsOfTransportation ($approvedFormsOfTransportation) {
        $this->approvedFormsOfTransportation = $approvedFormsOfTransportation;
    }

    public function addApprovedFormsOfTransportation($approvedFormsOfTransportation) {
        foreach($approvedFormsOfTransportation as $aft) {
            //$aft->setLadocRestraintCertified($this);
            $this->approvedFormsOfTransportation->add($aft);
        }
    }

    public function removeApprovedFormsOfTransportation($approvedFormsOfTransportation) {
        foreach($approvedFormsOfTransportation as $aft) {
            //$aft->setLadocRestraintCertified(null);
            $this->approvedFormsOfTransportation->removeElement($aft);
        }
    }

    public function getCreatedBy () {
        return $this->createdBy;
    }

    public function setCreatedBy ($createdBy) {
        $this->createdBy = $createdBy;
    }

    public function getApprovedBy () {
        return $this->approvedBy;
    }

    public function setApprovedBy ($approvedBy) {
        $this->approvedBy = $approvedBy;
    }

    public function getApprovedDate () {
        return $this->approvedDate;
    }

    public function setApprovedDate ($approvedDate) {
        $this->approvedDate = $approvedDate;
    }

    public function getPrerequisites () {
        return $this->prerequisites;
    }

    public function setPrerequisites ($prerequisites) {
        $this->prerequisites = $prerequisites;
    }

    public function setLadocRestraintCertifiedAttachments($attachments) {
        $this->ladocRestraintCertifiedAttachments = $attachments;
    }

    public function getLadocRestraintCertifiedAttachments() {
        return $this->ladocRestraintCertifiedAttachments;
    }

    public function setLadocRestraintCertifiedDocuments($documents) {
        $this->ladocRestraintCertifiedDocuments = $documents;
    }

    public function getLadocRestraintCertifiedDocuments() {
        return $this->ladocRestraintCertifiedDocuments;
    }

    public function getAttachments() {
        return $this->ladocRestraintCertifiedAttachments;
    }

    public function removeAttachments() {
        $this->ladocRestraintCertifiedAttachments->clear();
    }

    public function removeLadocRestraintCertifiedAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLadocRestraintCertified(null);
            $this->ladocRestraintCertifiedAttachments->removeElement($attachment);
        }
    }

    public function addLadocRestraintCertifiedAttachments($attachments) {
        foreach($attachments as $attachment) {
            $attachment->setLadocRestraintCertified($this);
            $this->ladocRestraintCertifiedAttachments->add($attachment);
        }
    }

    public function setLadocDocumentationWithTypeChecked (LadocDocumentation $ladocDocumentation) {
        if($ladocDocumentation->getType() == 'load')
            $this->setLoadDocumentation($ladocDocumentation);
        elseif($ladocDocumentation->getType() == 'carrier')
            $this->setCarrierDocumentation($ladocDocumentation);
    }

    public function getTitle($middleText) {
        return $this->getLoadDocumentation()->getBasicInformation()->getApprovedName() . ' ' . $middleText . ' ' .
            $this->getCarrierDocumentation()->getBasicInformation()->getApprovedName();
    }
}