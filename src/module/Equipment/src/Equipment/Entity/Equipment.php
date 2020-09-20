<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipment
 *
 * @ORM\Table(name="equipment")
 * @ORM\Entity(repositoryClass="Equipment\Repository\Equipment")
 */
class Equipment extends BaseEquipment {
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Feature")
     * @ORM\JoinTable(name="equipment_feature_override_to_feature",
     *   joinColumns={
     *     @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="feature_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $featureOverrides;

    /**
     * @var string
     *
     * @ORM\Column(name="`nsn`", type="string", length=50, nullable=true)
     */
    protected $nsn;

    /**
     * @var string
     *
     * @ORM\Column(name="`sap`", type="string", nullable=true)
     */
    protected $sap;

    /**
     * @var string
     *
     * @ORM\Column(name="`vendor_part`", type="string", nullable=true)
     */
    protected $vendorPart;


    /**
     * These fields bellow belong to Vedos Mechanical Equipments ------------------------------------------
     */

    /**
     * @var float
     *
     * @ORM\Column(name="wll", type="decimal", scale=2, nullable=true)
     */
    protected $wll;

    /**
     * @var float
     *
     * @ORM\Column(name="length", type="decimal", scale=2, nullable=true)
     */
    protected $length;

    /**
     * @var string
     *
     * @ORM\Column(name="material_quality", type="string", length=255, nullable=true)
     */
    protected $materialQuality;

    /**
     * @var string
     *
     * @ORM\Column(name="standard", type="string", length=255, nullable=true)
     */
    protected $standard;

    /**
     * @var string
     *
     * @ORM\Column(name="type_approval", type="string", length=255, nullable=true)
     */
    protected $typeApproval;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organization")
     * @ORM\JoinColumn(name="control_organ_organization_id", referencedColumnName="organization_id")
     */
    protected $controlOrgan;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->featureOverrides = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeatureOverrides() {
        return $this->featureOverrides;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $featureOverrides
     */
    public function setFeatureOverrides($featureOverrides) {
        $this->featureOverrides = $featureOverrides;
    }

    public function removeFeatureOverrides($featureOverrides) {
        foreach($featureOverrides as $featureOverride) {
            $this->featureOverrides->removeElement($featureOverride);
        }
    }

    public function addFeatureOverrides($featureOverrides) {
        foreach($featureOverrides as $featureOverride) {
            $this->featureOverrides->add($featureOverride);
        }
    }

    public function getNsn() {
        return $this->nsn;
    }

    public function getFormattedNsn()
    {
        if ($this->nsn != null && strlen($this->nsn) === 13) {
            return substr($this->nsn, 0, 4) . " " .
                substr($this->nsn, 4, 2) . " " .
                substr($this->nsn, 6, 3) . " " .
                substr($this->nsn, 9, 4);
        }

        return $this->nsn;
    }

    public function setNsn($nsn) {
        $this->nsn = $nsn;

        return $this;
    }

    public function getSap() {
        return $this->sap;
    }

    public function setSap($sap) {
        $this->sap = $sap;

        return $this;
    }

    public function getVendorPart() {
        return $this->vendorPart;
    }

    public function setVendorPart($vendorPart) {
        $this->vendorPart = $vendorPart;

        return $this;
    }

    /**
     * @return float
     */
    public function getWll() {
        return $this->wll;
    }

    /**
     * @param float $wll
     */
    public function setWll($wll) {
        $this->wll = $wll;
    }

    /**
     * @return float
     */
    public function getLength() {
        /*if (is_string($this->length)) {
            $this->length = (float) str_replace(',', '.', $this->length);
        }*/
        return $this->length;
    }

    /**
     * @param float $length
     */
    public function setLength($length) {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getMaterialQuality() {
        return $this->materialQuality;
    }

    /**
     * @param string $materialQuality
     */
    public function setMaterialQuality($materialQuality) {
        $this->materialQuality = $materialQuality;
    }

    /**
     * @return string
     */
    public function getStandard() {
        return $this->standard;
    }

    /**
     * @param string $standard
     */
    public function setStandard($standard) {
        $this->standard = $standard;
    }

    /**
     * @return string
     */
    public function getTypeApproval() {
        return $this->typeApproval;
    }

    /**
     * @param string $typeApproval
     */
    public function setTypeApproval($typeApproval) {
        $this->typeApproval = $typeApproval;
    }

    /**
     * @return string
     */
    public function getControlOrgan() {
        return $this->controlOrgan;
    }

    /**
     * @param string $controlOrgan
     */
    public function setControlOrgan($controlOrgan) {
        $this->controlOrgan = $controlOrgan;
    }
}
