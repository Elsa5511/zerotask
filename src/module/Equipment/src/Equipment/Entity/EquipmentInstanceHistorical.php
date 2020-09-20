<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentInstanceHistorical
 *
 * @ORM\Table(name="equipment_instance_history")
 * @ORM\Entity
 */
class EquipmentInstanceHistorical extends \Sysco\Aurora\Doctrine\ORM\Entity {

    /**
     * @var integer
     *
     * @ORM\Column(name="equipment_instance_historical_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $equipmentInstanceHistoricalId;

    /**
     * @var string
     *
     * @ORM\Column(name="serial_number", type="string", length=50, nullable=false)
     */
    protected $serialNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="reg_number", type="string", length=50, nullable=true)
     */
    protected $regNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="batch_number", type="string", length=50, nullable=true)
     */
    protected $batchNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="certificate_number", type="string", length=50, nullable=true)
     */
    protected $certificateNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchase_date", type="datetime", nullable=true)
     */
    protected $purchaseDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="technical_lifetime", type="datetime", nullable=true)
     */
    protected $technicalLifetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="guarantee_time", type="datetime", nullable=true)
     */
    protected $guaranteeTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reception_control", type="datetime", nullable=true)
     */
    protected $receptionControl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_time_used", type="datetime", nullable=true)
     */
    protected $firstTimeUsed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="periodic_control_date", type="datetime", nullable=true)
     */
    protected $periodicControlDate;

    /**
     * @var string
     *
     * @ORM\Column(name="control_status", type="string", length=50, nullable=true)
     */
    protected $controlStatus = 'Approved';

    /**
     * @var string
     *
     * @ORM\Column(name="visual_control", type="string", length=1, nullable=true)
     */
    protected $visualControl;

    /**
     * @var string
     *
     * @ORM\Column(name="order_number", type="string", length=100, nullable=true)
     */
    protected $orderNumber;

    /**
     *
     * @var string
     * @ORM\Column(name="rfid", type="string", length=100, nullable=true)
     */
    protected $rfid;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="text", nullable=true)
     */
    protected $remarks;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_id", type="integer", nullable=true)
     */
    protected $parentId = 0;

    /**
     * @var \Application\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="owner", referencedColumnName="organization_id")
     * })
     */
    protected $owner;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     * })
     */
    protected $equipment;

    /**
     * @var \Application\Entity\LocationTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\LocationTaxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="location", referencedColumnName="location_taxonomy_id")
     * })
     */
    protected $location;

    /**
     * @var \Equipment\Entity\EquipmentInstanceTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstanceTaxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usage_status", referencedColumnName="equipment_instance_taxonomy_id")
     * })
     */
    protected $usageStatus;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="checked_out", type="boolean", nullable=false)
     */
    protected $checkedOut = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    protected $dateUpdated;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="user_id", nullable=true)
     * })
     */
    protected $updatedBy;

    /**
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id")
     * })
     */
    protected $equipmentInstance;

    public function getEquipmentInstanceHistoricalId() {
        return $this->equipmentInstanceHistoricalId;
    }

    public function getEquipmentInstance() {
        return $this->equipmentInstance;
    }

    public function setEquipmentInstance($equipmentInstance) {
        $this->equipmentInstance = $equipmentInstance;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     * @return EquipmentInstance
     */
    public function setSerialNumber($serialNumber) {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string 
     */
    public function getSerialNumber() {
        return $this->serialNumber;
    }

    /**
     * Set regNumber
     *
     * @param string $regNumber
     * @return EquipmentInstance
     */
    public function setRegNumber($regNumber) {
        $this->regNumber = $regNumber;

        return $this;
    }

    /**
     * Get regNumber
     *
     * @return string
     */
    public function getRegNumber() {
        return $this->regNumber;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * Set parentId
     *
     * @param integer $parentId
     * @return Integer
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;

        return $this;
    }

    /**
     * Set batchNumber
     *
     * @param string $batchNumber
     * @return EquipmentInstance
     */
    public function setBatchNumber($batchNumber) {
        $this->batchNumber = $batchNumber;

        return $this;
    }

    /**
     * Get batchNumber
     *
     * @return string 
     */
    public function getBatchNumber() {
        return $this->batchNumber;
    }

    /**
     * Set certificateNumber
     *
     * @param string $certificateNumber
     * @return EquipmentInstance
     */
    public function setCertificateNumber($certificateNumber) {
        $this->certificateNumber = $certificateNumber;

        return $this;
    }

    /**
     * Get certificateNumber
     *
     * @return string 
     */
    public function getCertificateNumber() {
        return $this->certificateNumber;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     * @return EquipmentInstance
     */
    public function setPurchaseDate(\DateTime $purchaseDate = null) {
        $this->purchaseDate = $purchaseDate;
        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime 
     */
    public function getPurchaseDate() {
        return $this->purchaseDate;
    }

    /**
     * Set technicalLifetime
     *
     * @param \DateTime $technicalLifetime
     * @return EquipmentInstance
     */
    public function setTechnicalLifetime($technicalLifetime) {
        $this->technicalLifetime = $technicalLifetime;

        return $this;
    }

    /**
     * Get technicalLifetime
     *
     * @return \DateTime 
     */
    public function getTechnicalLifetime() {
        return $this->technicalLifetime;
    }

    /**
     * Set guaranteeTime
     *
     * @param \DateTime $guaranteeTime
     * @return EquipmentInstance
     */
    public function setGuaranteeTime($guaranteeTime) {
        $this->guaranteeTime = $guaranteeTime;

        return $this;
    }

    /**
     * Get guaranteeTime
     *
     * @return \DateTime 
     */
    public function getGuaranteeTime() {
        return $this->guaranteeTime;
    }

    /**
     * Get firstTimeUsed
     *
     * @return \DateTime 
     */
    public function getFirstTimeUsed() {
        return $this->firstTimeUsed;
    }

    /**
     * Set firstTimeUsed
     *
     * @param \DateTime $firstTimeUsed
     * @return EquipmentInstance
     */
    public function setFirstTimeUsed($firstTimeUsed) {
        $this->firstTimeUsed = $firstTimeUsed;
        return $this;
    }

    /**
     * Set receptionControl
     *
     * @param \DateTime $receptionControl
     * @return EquipmentInstance
     */
    public function setReceptionControl($receptionControl) {
        $this->receptionControl = $receptionControl;

        return $this;
    }

    /**
     * Get receptionControl
     *
     * @return \DateTime 
     */
    public function getReceptionControl() {
        return $this->receptionControl;
    }

    /**
     * Set periodicControlDate
     *
     * @param \DateTime $periodicControlDate
     * @return EquipmentInstance
     */
    public function setPeriodicControlDate($periodicControlDate) {
        $this->periodicControlDate = $periodicControlDate;

        return $this;
    }

    /**
     * Get periodicControlDate
     *
     * @return \DateTime 
     */
    public function getPeriodicControlDate() {
        return $this->periodicControlDate;
    }

    /**
     * Set controlStatus
     *
     * @param string $controlStatus
     * @return EquipmentInstance
     */
    public function setControlStatus($controlStatus) {
        $this->controlStatus = $controlStatus;

        return $this;
    }

    /**
     * Get controlStatus
     *
     * @return string
     */
    public function getControlStatus() {
        return $this->controlStatus;
    }

    public function getControlStatusOrExpiredStatus() {
        $periodicControlDate = $this->getPeriodicControlDate();
        $differenceTime = $periodicControlDate->diff(new AuroraDateTime("now"));
        $isLessThanToday = ($differenceTime->days > 0 && $differenceTime->invert === 0);

        if ($isLessThanToday) {
            return 'expired';
        } else {
            return $this->getControlStatus();
        }
    }

    /**
     * Set visualControl
     *
     * @param string $visualControl
     * @return EquipmentInstance
     */
    public function setVisualControl($visualControl) {
        $this->visualControl = $visualControl;

        return $this;
    }

    /**
     * Get visualControl
     *
     * @return string 
     */
    public function getVisualControl() {
        return $this->visualControl;
    }

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return EquipmentInstance
     */
    public function setOrderNumber($orderNumber) {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return string 
     */
    public function getOrderNumber() {
        return $this->orderNumber;
    }

    /**
     * Get RFID
     * 
     * @return string
     */
    public function getRfid() {
        return $this->rfid;
    }

    /**
     * Set RFID
     * 
     * @param type $rfid
     * @return EquipmentInstance
     */
    public function setRfid($rfid) {
        $this->rfid = $rfid;

        return $this;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     * @return EquipmentInstance
     */
    public function setRemarks($remarks) {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string 
     */
    public function getRemarks() {
        return $this->remarks;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return EquipmentInstance
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set owner
     *
     * @param \Application\Entity\Organization $owner
     * @return EquipmentInstance
     */
    public function setOwner(\Application\Entity\Organization $owner = null) {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \Application\Entity\Organization 
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * Set equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return EquipmentInstance
     */
    public function setEquipment(\Equipment\Entity\Equipment $equipment = null) {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getEquipment() {
        return $this->equipment;
    }

    /**
     * Set location
     *
     * @param \Application\Entity\LocationTaxonomy $location
     * @return Location
     */
    public function setLocation(\Application\Entity\LocationTaxonomy $location = null) {
        $this->location = $location;
        return $this;
    }

    /**
     * Get location
     *
     * @return \Application\Entity\LocationTaxonomy 
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Set usageStatus
     *
     * @param \Application\Entity\BaseTaxonomy $usageStatus
     * @return EquipmentInstance
     */
    public function setUsageStatus(\Application\Entity\BaseTaxonomy $usageStatus = null) {
        $this->usageStatus = $usageStatus;

        return $this;
    }

    /**
     * Get usageStatus
     *
     * @return \Application\Entity\BaseTaxonomy 
     */
    public function getUsageStatus() {
        return $this->usageStatus;
    }

    /**
     * 
     * @return boolean
     */
    public function isCheckedOut() {
        return $this->checkedOut;
    }

    /**
     * 
     * @param boolean $checkedOut
     */
    public function setCheckedOut($checkedOut) {
        $this->checkedOut = $checkedOut;
    }

    public function getDateUpdated() {
        return $this->dateUpdated;
    }

    public function setDateUpdated(\DateTime $dateUpdated) {
        $this->dateUpdated = $dateUpdated;
    }

    public function getUpdatedBy() {
        return $this->updatedBy;
    }

    public function setUpdatedBy(\Application\Entity\User $updatedBy) {
        $this->updatedBy = $updatedBy;
    }

    public function __toString() {
        return $this->getSerialNumber();
    }

}
