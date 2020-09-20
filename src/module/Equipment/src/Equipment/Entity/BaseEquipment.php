<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acl\Entity\AbstractEntity;


abstract class BaseEquipment extends AbstractEntity {
    const INSTANCE_TYPE_STANDARD = "standard";
    const INSTANCE_TYPE_CONTAINER = "container";

    public function __toString() {
        return $this->title;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="equipment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $equipmentId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    protected $code;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer", nullable=true)
     */
    protected $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinTable(name="equipment_has_to_be_used_with",
     *      joinColumns={@ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="has_to_be_used_with_id", referencedColumnName="equipment_id")}
     *      )
     */
    protected $hasToBeUsedWith;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinTable(name="equipment_can_be_used_with",
     *      joinColumns={@ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="can_be_used_with_id", referencedColumnName="equipment_id")}
     *      )
     */
    protected $canBeUsedWith;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    protected $status = 'active';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    protected $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    protected $dateUpdate;

    /**
     * @var string
     *
     * @ORM\Column(name="feature_image", type="string", length=200, nullable=true)
     */
    protected $featureImage;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    protected $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Equipment\Entity\EquipmentTaxonomy", inversedBy="equipment")
     * @ORM\JoinTable(name="equipment_taxonomy_relationship",
     *   joinColumns={
     *     @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="equipment_taxonomy_id", referencedColumnName="equipment_taxonomy_id")
     *   }
     * )
     */
    protected $equipmentTaxonomy;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_interval_days", type="integer", nullable=true)
     */
    protected $controlIntervalByDays;

    /**
     * @var string (standard|container)
     *
     * @ORM\Column(name="instance_type", type="string", nullable=false)
     */
    protected $instanceType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Equipment\Entity\EquipmentInstance", mappedBy="equipment")
     */
    protected $instances;

    /**
     * @var \LadocDocumentation\Entity\LadocDocumentation
     *
     * @ORM\OneToOne(targetEntity="LadocDocumentation\Entity\LadocDocumentation", mappedBy="equipment")
     */
    protected $ladocDocumentation;

    /**
     * Constructor
     */
    public function __construct() {
        $this->equipmentTaxonomy = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hasToBeUsedWith = new \Doctrine\Common\Collections\ArrayCollection();
        $this->canBeUsedWith = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get equipmentId
     *
     * @return integer
     */
    public function getEquipmentId() {
        return $this->equipmentId;
    }

    public function setEquipmentId($equipmentId) {
        $this->equipmentId = $equipmentId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Equipment
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Equipment
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Equipment
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Equipment
     */
    public function setOrder($order) {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder() {
        return $this->order;
    }

    public function setHasToBeUsedWith($hasToBeUsedWith) {
        $this->hasToBeUsedWith = $hasToBeUsedWith;
    }

    public function getHasToBeUsedWith() {
        return $this->hasToBeUsedWith;
    }

    public function setCanBeUsedWith($canBeUsedWith) {
        $this->canBeUsedWith = $canBeUsedWith;
    }

    public function getCanBeUsedWith() {
        return $this->canBeUsedWith;
    }

    private function addEquipmentToBeUsedWith(\Equipment\Entity\Equipment $equipment) {
        $this->equipment = $equipment;
        return $this;
    }

    /**
     * Add hasToBeUsedWith
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     *
     */
    public function addHasToBeUsedWith(\Doctrine\Common\Collections\ArrayCollection $hasToBeUsedWith = null) {

        foreach ($hasToBeUsedWith as $hasToBeUsedWithEquipment) {
            $hasToBeUsedWithEquipment->addEquipmentToBeUsedWith($this);
            $this->hasToBeUsedWith->add($hasToBeUsedWithEquipment);
        }
    }

    /**
     * Remove hasToBeUsedWith
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     *
     */
    public function removeHasToBeUsedWith(\Doctrine\Common\Collections\ArrayCollection $hasToBeUsedWith = null) {
        foreach ($hasToBeUsedWith as $hasToBeUsed) {
            $this->hasToBeUsedWith->removeElement($hasToBeUsed);
        }
    }

    /**
     * Add canBeUsedWith
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     *
     */
    public function addCanBeUsedWith(\Doctrine\Common\Collections\ArrayCollection $canBeUsedWith) {

        foreach ($canBeUsedWith as $canBeUsedWithEquipment) {
            $canBeUsedWithEquipment->addEquipmentToBeUsedWith($this);
            $this->canBeUsedWith->add($canBeUsedWithEquipment);
        }
    }

    /**
     * Remove canBeUsedWith
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     *
     */
    public function removeCanBeUsedWith(\Doctrine\Common\Collections\ArrayCollection $canBeUsedWith) {

        foreach ($canBeUsedWith as $canBeUsed) {
            $this->canBeUsedWith->removeElement($canBeUsed);
        }
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Equipment
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

    public function getActiveStatus() {
        return $this->getStatus();
    }

    public function setActiveStatus($activeStatus) {
        return $this->setStatus($activeStatus);
    }


    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return Equipment
     */
    public function setDateAdd($dateAdd) {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime
     */
    public function getDateAdd() {
        return $this->dateAdd;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Equipment
     */
    public function setDateUpdate($dateUpdate) {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate() {
        return $this->dateUpdate;
    }

    /**
     * Set featureImage
     *
     * @param string $featureImage
     * @return Equipment
     */
    public function setFeatureImage($featureImage) {
        $this->featureImage = $featureImage;

        return $this;
    }

    /**
     * Get featureImage
     *
     * @return string
     */
    public function getFeatureImage() {
        return $this->featureImage;
    }

    /**
     * Set user
     *
     * @param \Application\Entity\User $user
     * @return Equipment
     */
    public function setUser(\Application\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Add equipmentTaxonomy
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     *
     */
    public function addEquipmentTaxonomy(\Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies) {

        foreach ($equipmentTaxonomies as $equipmentTaxonomy) {
            $equipmentTaxonomy->addEquipment($this);
            $this->equipmentTaxonomy->add($equipmentTaxonomy);
        }
    }

    /**
     * Remove equipmentTaxonomy
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies
     */
    public function removeEquipmentTaxonomy(\Doctrine\Common\Collections\ArrayCollection $equipmentTaxonomies) {
        foreach ($equipmentTaxonomies as $equipmentTaxonomy) {
            $equipmentTaxonomy->removeEquipment($this);
            $this->equipmentTaxonomy->removeElement($equipmentTaxonomy);
        }
    }

    /**
     * Get equipmentTaxonomy
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipmentTaxonomy() {
        return $this->equipmentTaxonomy;
    }

    public function setEquipmentTaxonomy(\Doctrine\Common\Collections\Collection $equipmentTaxonomy) {
        $this->equipmentTaxonomy = $equipmentTaxonomy;
    }

    /**
     * @return EquipmentTaxonomy
     */
    public function getFirstEquipmentTaxonomy() {
        return $this->equipmentTaxonomy[0];
    }

    public function getFormattedCompetenceAreas() {
        $competenceAreasArray = array();
        foreach ($this->getEquipmentTaxonomy() as $taxonomy) {
            $competenceArea = $taxonomy->getCompetenceAreaTaxonomy();
            if ($competenceArea) $competenceAreasArray[] = $competenceArea->getName();
        }
        return implode(', ', $competenceAreasArray);
    }

    /**
     * Set control interval By Days
     *
     * @param integer $controlIntervalByDays
     * @return Equipment
     */
    public function setControlIntervalByDays($controlIntervalByDays) {
        $this->controlIntervalByDays = $controlIntervalByDays;

        return $this;
    }

    /**
     * Get control interval By Days
     *
     * @return Datetime
     */
    public function getControlIntervalByDays() {
        return $this->controlIntervalByDays;
    }

    /**
     * @return string
     */
    public function getInstanceType() {
        return $this->instanceType;
    }

    /**
     * @param string $instanceType
     */
    public function setInstanceType($instanceType) {
        $this->instanceType = $instanceType;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInstances() {
        return $this->instances;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $instances
     */
    public function setInstances($instances) {
        $this->instances = $instances;
    }

    /**
     * @return \LadocDocumentation\Entity\LadocDocumentation
     */
    public function getLadocDocumentation()
    {
        return $this->ladocDocumentation;
    }

    /**
     * @param \LadocDocumentation\Entity\LadocDocumentation $ladocDocumentation
     */
    public function setLadocDocumentation($ladocDocumentation)
    {
        $this->ladocDocumentation = $ladocDocumentation;
    }
}