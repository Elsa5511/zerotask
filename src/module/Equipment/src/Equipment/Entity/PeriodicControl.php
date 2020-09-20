<?php

namespace Equipment\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Equipment\Entity\PeriodicControlAttachment;
use Doctrine\ORM\Mapping as ORM;

/**
 * PeriodicControl
 *
 * @ORM\Table(name="periodic_control")
 * @ORM\Entity(repositoryClass="Equipment\Repository\PeriodicControl")
 */
class PeriodicControl extends \Acl\Entity\AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="periodic_control_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $periodicControlId;

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
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competent_person", referencedColumnName="user_id")
     * })
     */
    protected $competentPerson;

    /**
     * @var \Application\Entity\Organization
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organization")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organ_id", referencedColumnName="organization_id")
     * })
     */
    protected $expertiseOrgan;

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
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\ManyToMany(targetEntity="ControlPointResult", cascade={"persist"})
     * @ORM\JoinTable(name="periodic_controls_control_point_results",
     *      joinColumns={
     *          @ORM\JoinColumn(name="periodic_control_id", referencedColumnName="periodic_control_id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="control_point_result_id", 
     *                          referencedColumnName="control_point_result_id", 
     *                          unique=true)
     *      }
     * )
     **/
    protected $controlPointResultCollection;

    /**
     * @var \Equipment\Entity\EquipmentInstance
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentInstance", inversedBy="periodicControls")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_instance_id", referencedColumnName="equipment_instance_id", onDelete="CASCADE")
     * })
     */
    protected $equipmentInstance;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Equipment\Entity\PeriodicControlAttachment", mappedBy="periodicControl", cascade={"all"})
     */
    protected $periodicControlAttachments;


    public function __construct()
    {
        $this->createdTime = new \DateTime();
        $this->controlPointResultCollection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->periodicControlAttachments = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function getPeriodicControlId()
    {
        return $this->periodicControlId;
    }

    public function setPeriodicControlId($periodicControlId)
    {
        $this->periodicControlId = $periodicControlId;
    }

    public function getRegisteredBy()
    {
        return $this->registeredBy;
    }

    public function setRegisteredBy(\Application\Entity\User $registeredBy)
    {
        $this->registeredBy = $registeredBy;
    }
        
    public function getControlDate()
    {
        return $this->controlDate;
    }

    public function setControlDate(\DateTime $controlDate)
    {
        $this->controlDate = $controlDate;
    }

    public function getNextControlDate()
    {
        return $this->nextControlDate;
    }

    public function setNextControlDate(\DateTime $nextControlDate)
    {
        $this->nextControlDate = $nextControlDate;
    }

    public function getCompetentPerson()
    {
        return $this->competentPerson;
    }

    public function setCompetentPerson(\Application\Entity\User $competentPerson)
    {
        $this->competentPerson = $competentPerson;
    }

    public function getExpertiseOrgan()
    {
        return $this->expertiseOrgan;
    }

    public function setExpertiseOrgan(\Application\Entity\Organization $expertiseOrgan)
    {
        $this->expertiseOrgan = $expertiseOrgan;
    }

    public function getControlStatus()
    {
        return $this->controlStatus;
    }

    public function setControlStatus(\Equipment\Entity\PeriodicControlTaxonomy $controlStatus)
    {
        $this->controlStatus = $controlStatus;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getControlPointResultCollection()
    {
        return $this->controlPointResultCollection;
    }

    public function getOrderedControlPointResults() {
        // Initializing relationships to get rid of warning.
        // getOrder() will cause doctrine to get its controlPointToTemplate relation.
        foreach ($this->controlPointResultCollection as $controlPointResult) {
            $controlPointResult->getOrder();
        }

        $iterator = $this->controlPointResultCollection->getIterator();
        $iterator->uasort(function ($a, $b) {
            if (!$b->getOrder()) {
                return -1;
            }
            if (!$a->getOrder()) {
                return 1;
            }
            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        });
        return new ArrayCollection(iterator_to_array($iterator));
    }


    public function setControlPointResultCollection($controlPointResultCollection)
    {
        $this->controlPointResultCollection = $controlPointResultCollection;
    }

    public function addControlPointResultCollection(\Doctrine\Common\Collections\ArrayCollection $controlPointResults)
    {
        foreach ($controlPointResults as $controlPointResult) {
            $this->controlPointResultCollection->add($controlPointResult);
        }
    }

    public function removeControlPointResultCollection(\Doctrine\Common\Collections\ArrayCollection $controlPointResults)
    {
        foreach ($controlPointResults as $controlPointResult) {
            $this->controlPointResultCollection->removeElement($controlPointResult);
        }
    }
    
    /**
     *
     * @param \Equipment\Entity\ControlPointResult $controlPointResult
     * @return 
     */
    public function addControlPointResult(\Equipment\Entity\ControlPointResult $controlPointResult)
    {
        $this->controlPointResultCollection[$controlPointResult->getControlPointResultId()] = $controlPointResult;

        return $this;
    }

    /**
     * 
     *
     * @param \Equipment\Entity\ControlPointResult $controlPointResult
     */
    public function removeControlPointResult(\Equipment\Entity\ControlPointResult $controlPointResult)
    {
        $this->controlPointResultCollection->removeElement($controlPointResult);
    }

    public function getEquipmentInstance()
    {
        return $this->equipmentInstance;
    }

    public function setEquipmentInstance(\Equipment\Entity\EquipmentInstance $equipmentInstance)
    {
        $this->equipmentInstance = $equipmentInstance;
    }


    public function getPeriodicControlAttachments()
    {
        return $this->periodicControlAttachments;
    }

    public function setPeriodicControlAttachments($periodicControlAttachments)
    {
        $this->periodicControlAttachments = $periodicControlAttachments;
    }

    public function addPeriodicControlAttachment(PeriodicControlAttachment $periodicControlAttachment)
    {
        $this->periodicControlAttachments->add($periodicControlAttachment);
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


    public function __clone()
    {
        if ($this->periodicControlId) {
            $this->setPeriodicControlId(null);
        }
        if ($this->controlPointResultCollection) {
            $clonedCollection = new \Doctrine\Common\Collections\ArrayCollection();
            
            foreach($this->controlPointResultCollection as $controlPointResult) {
                $clonedCollection->add(clone $controlPointResult);
            }
            
            $this->controlPointResultCollection = $clonedCollection;
        }
        if ($this->periodicControlAttachments) {
            $clonedAttachments = new \Doctrine\Common\Collections\ArrayCollection();

            foreach($this->periodicControlAttachments as $periodicControlAttachment) {
                $clonedAttachments->add(clone $periodicControlAttachment);
            }

            $this->periodicControlAttachments = $clonedAttachments;
        }
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
