<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sysco\Aurora\Doctrine\ORM\Entity;

/**
 * Checkin for Equipment Instance
 *
 * @ORM\Table(name="checkin_equipment_instance")
 * @ORM\Entity
 */
class Checkin extends Entity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="checkin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $checkinId;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="checked_by", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $checkedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checkin_date", type="datetime", nullable=true)
     */
    protected $checkinDate;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var \Equipment\Entity\EquipmentInstance
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance", inversedBy="equipmentInstance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id",onDelete="CASCADE")
     * })
     */
    protected $equipmentInstance;

    public function getCheckinId()
    {
        return $this->checkinId;
    }

    public function setCheckinId($checkinId)
    {
        $this->checkinId = $checkinId;
    }

    public function getCheckedBy()
    {
        return $this->checkedBy;
    }

    public function setCheckedBy(\Application\Entity\User $checkedBy)
    {
        $this->checkedBy = $checkedBy;
    }

    public function getCheckinDate()
    {
        return $this->checkinDate;
    }

    public function setCheckinDate(\DateTime $checkinDate)
    {
        $this->checkinDate = $checkinDate;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getEquipmentInstance()
    {
        return $this->equipmentInstance;
    }

    public function setEquipmentInstance(\Equipment\Entity\EquipmentInstance $equipmentInstance)
    {
        $this->equipmentInstance = $equipmentInstance;
    }

}
