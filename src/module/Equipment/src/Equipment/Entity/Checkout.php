<?php
namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sysco\Aurora\Doctrine\ORM\Entity;

/**
 * Checkout for Equipment Instance
 *
 * @ORM\Table(name="checkout_equipment_instance")
 * @ORM\Entity
 */
class Checkout extends Entity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="checkout_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $checkoutId;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checkout_date", type="datetime", nullable=false)
     */
    protected $checkoutDate;
    
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
     * @var \Application\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organization_id", referencedColumnName="organization_id")
     * })
     */
    protected $organization;

     /**
     * @var string
     *
     * @ORM\Column(name="contact_person", type="string", length=50, nullable=true)
     */
    protected $contactPerson;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_person_phone", type="string", length=50, nullable=true)
     */
    protected $contactPersonPhone;
    
    /**
     * @var string
     *
     * @ORM\Column(name="contact_person_position", type="string", length=50, nullable=true)
     */
    protected $contactPersonPosition;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var \Equipment\Entity\EquipmentInstance
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id",onDelete="CASCADE")
     * })
     */
    protected $equipmentInstance;


    public function getCheckoutId()
    {
        return $this->checkoutId;
    }

    public function setCheckoutId($checkoutId)
    {
        $this->checkoutId = $checkoutId;
    }

    public function getCheckedBy()
    {
        return $this->checkedBy;
    }

    public function setCheckedBy(\Application\Entity\User $checkedBy)
    {
        $this->checkedBy = $checkedBy;
    }
        
    public function getCheckoutDate()
    {
        return $this->checkoutDate;
    }

    public function setCheckoutDate(\DateTime $checkoutDate)
    {
        $this->checkoutDate = $checkoutDate;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    public function setOrganization(\Application\Entity\Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getCheckinDate()
    {
        return $this->checkinDate;
    }

    public function setCheckinDate(\DateTime $checkinDate)
    {
        $this->checkinDate = $checkinDate;
    }

    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;
    }

    public function getContactPersonPhone()
    {
        return $this->contactPersonPhone;
    }

    public function setContactPersonPhone($contactPersonPhone)
    {
        $this->contactPersonPhone = $contactPersonPhone;
    }

    public function getContactPersonPosition()
    {
        return $this->contactPersonPosition;
    }

    public function setContactPersonPosition($contactPersonPosition)
    {
        $this->contactPersonPosition = $contactPersonPosition;
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
    
    public function __toString()
    {    
        $string = "";
        if($this->getEquipmentInstance()) {
            $string = $this->getEquipmentInstance()->getSerialNumber();
        }
        return $string;
            
    }

}
