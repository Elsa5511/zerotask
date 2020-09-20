<?php

namespace Equipment\Entity;

use Application\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * VisualControl
 *
 * @ORM\Table(name="visual_control")
 * @ORM\Entity(repositoryClass="Equipment\Repository\VisualControl")
 */
class VisualControl extends \Acl\Entity\AbstractEntity {

    /**
     * @var integer
     *
     * @ORM\Column(name="visual_control_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $visualControlId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdTime;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="registered_by", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $registeredBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="control_date", type="datetime", nullable=true)
     */
    protected $controlDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="next_control_date", type="datetime", nullable=true)
     */
    protected $nextControlDate;

    /**
     * @var \Equipment\Entity\PeriodicControlTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\PeriodicControlTaxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="control_status", referencedColumnName="periodic_control_taxonomy_id")
     * })
     */
    protected $controlStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="remarks", type="text", nullable=true)
     */
    protected $remarks;

    /**
     * @var \Equipment\Entity\EquipmentInstance
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance", inversedBy="periodicControls")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id", onDelete="CASCADE")
     * })
     */
    protected $equipmentInstance;


    public function __construct() {
        $this->createdTime = new \DateTime();
    }

    public function getVisualControlId() {
        return $this->visualControlId;
    }

    public function setVisualControlId($visualControlId) {
        $this->visualControlId = $visualControlId;
    }

    public function getRegisteredBy() {
        return $this->registeredBy;
    }

    public function setRegisteredBy(\Application\Entity\User $registeredBy) {
        $this->registeredBy = $registeredBy;
    }

    public function getControlDate() {
        return $this->controlDate;
    }

    public function setControlDate(\DateTime $controlDate) {
        $this->controlDate = $controlDate;
    }

    public function getNextControlDate() {
        return $this->nextControlDate;
    }

    public function setNextControlDate(\DateTime $nextControlDate) {
        $this->nextControlDate = $nextControlDate;
    }

    public function getControlStatus() {
        return $this->controlStatus;
    }

    public function setControlStatus(\Equipment\Entity\PeriodicControlTaxonomy $controlStatus) {
        $this->controlStatus = $controlStatus;
    }

    public function getRemarks() {
        return $this->remarks;
    }

    public function setRemarks($remarks) {
        $this->remarks = $remarks;
    }

    public function getEquipmentInstance() {
        return $this->equipmentInstance;
    }

    public function setEquipmentInstance(\Equipment\Entity\EquipmentInstance $equipmentInstance) {
        $this->equipmentInstance = $equipmentInstance;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedTime() {
        return $this->createdTime;
    }

    /**
     * @param \DateTime $createdTime
     */
    public function setCreatedTime($createdTime) {
        $this->createdTime = $createdTime;
    }


    /**
     * @param User $currentUser
     * @return bool
     */
    public function isDeletable($currentUser) {
        return ($this->isWithinTwentyFourHoursOfCreation() &&
            $this->isSameUserOrAdmin($currentUser));
    }

    /**
     * @return bool
     */
    private function isWithinTwentyFourHoursOfCreation() {
        $createdTime = clone $this->createdTime;
        $deleteLimit = $createdTime->add(new \DateInterval("PT24H"));
        $now = new \DateTime();
        return ($now <= $deleteLimit);
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    private function isSameUserOrAdmin($currentUser) {
        return ($this->registeredBy->getId() === $currentUser->getId()
            || $currentUser->hasRole('admin'));
    }


    public function __clone() {
        if ($this->visualControlId) {
            $this->setVisualControlId(null);
        }
    }

    public function __toString() {
        $string = "";
        if ($this->getEquipmentInstance()) {
            $string = $this->getEquipmentInstance()->getSerialNumber();
        }
        return $string;
    }
}
