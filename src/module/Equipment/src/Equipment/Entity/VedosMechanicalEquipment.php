<?php


namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Equipment\Repository\Equipment")
 */
class VedosMechanicalEquipment extends Equipment {

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
        if (is_string($this->length)) {
            $this->length = (float) str_replace(',', '.', $this->length);
        }
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

