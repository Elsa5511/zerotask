<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table("carrier_weight_and_dimensions")
 */
class CarrierWeightAndDimensions extends WeightAndDimensions {

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeight", cascade={"persist"}, inversedBy="wd1")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="own_weight_id", referencedColumnName="id")
     * })
     */
    protected $ownWeight;

    /**
     * @ORM\OneToOne(targetEntity="CarrierWeight", cascade={"persist"}, inversedBy="wd2")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="technical_weight_id", referencedColumnName="id")
     * })
     */
    protected $technicalWeight;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    protected $weightAdditionalInfo;

    /**
     * @ORM\OneToOne(targetEntity="CarrierDimensions", cascade={"persist"}, inversedBy="wd1")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="own_dimensions_id", referencedColumnName="id")
     * })
     */
    protected $ownDimensions;

    /**
     * @ORM\OneToOne(targetEntity="CarrierDimensions", cascade={"persist"}, inversedBy="wd2")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="loading_plan_dimensions_id", referencedColumnName="id")
     * })
     */
    protected $loadingPlanDimensions;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    protected $dimensionsAdditionalInfo;

    /**
     * @return \LadocDocumentation\Entity\CarrierWeight
     */
    public function getOwnWeight() {
        return $this->ownWeight;
    }

    public function setOwnWeight($ownWeight) {
        $this->ownWeight = $ownWeight;
    }

    /**
     * @return \LadocDocumentation\Entity\CarrierWeight
     */
    public function getTechnicalWeight() {
        return $this->technicalWeight;
    }

    public function setTechnicalWeight($technicalTotalWeight) {
        $this->technicalWeight = $technicalTotalWeight;
    }

    public function getWeightAdditionalInfo() {
        return $this->weightAdditionalInfo;
    }

    public function setWeightAdditionalInfo($weightAdditionalInfo) {
        $this->weightAdditionalInfo = $weightAdditionalInfo;
    }

    /**
     * @return \LadocDocumentation\Entity\CarrierDimensions
     */
    public function getOwnDimensions() {
        return $this->ownDimensions;
    }

    public function setOwnDimensions($ownDimensions) {
        $this->ownDimensions = $ownDimensions;
    }

    /**
     * @return \LadocDocumentation\Entity\CarrierDimensions
     */
    public function getLoadingPlanDimensions() {
        return $this->loadingPlanDimensions;
    }

    public function setLoadingPlanDimensions($LoadingPlanDimensions) {
        $this->loadingPlanDimensions = $LoadingPlanDimensions;
    }

    public function getDimensionsAdditionalInfo() {
        return $this->dimensionsAdditionalInfo;
    }

    public function setDimensionsAdditionalInfo($dimensionsAdditionalInfo) {
        $this->dimensionsAdditionalInfo = $dimensionsAdditionalInfo;
    }

    /**
     * For some reason, the references are not loaded. It probably has to do with
     * doctrine's lazy loading, but why it acts weird in this case I don't know.
     * Triggering the references so they will be loaded.
     */
    public function loadReferences() {
        $this->ownWeight->getDescription();
        $this->technicalWeight->getDescription();
        $this->ownDimensions->getDescription();
        $this->loadingPlanDimensions->getDescription();
    }

    public function getImageFiles() {
        $imageFiles = array(
            'own-weight' => array(),
            'technical-weight' => array(),
            'own-dimensions' => array(),
            'loading-plan-dimensions' => array()
        );

        foreach ($this->getOwnWeight()->getAttachments() as $attachment) {
            array_push($imageFiles['own-weight'], $attachment->getFile());
        }
        foreach ($this->getTechnicalWeight()->getAttachments() as $attachment) {
            array_push($imageFiles['technical-weight'], $attachment->getFile());
        }
        foreach ($this->getOwnDimensions()->getAttachments() as $attachment) {
            array_push($imageFiles['own-dimensions'], $attachment->getFile());
        }
        foreach ($this->getLoadingPlanDimensions()->getAttachments() as $attachment) {
            array_push($imageFiles['loading-plan-dimensions'], $attachment->getFile());
        }
        return $imageFiles;
    }
}