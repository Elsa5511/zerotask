<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipmentmeta
 *
 * @ORM\Table(name="equipmentmeta")
 * @ORM\Entity
 */
class Equipmentmeta extends \Acl\Entity\AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="equipmentmeta_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $equipmentmetaId;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=50, nullable=false)
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", nullable=false)
     */
    protected $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    protected $dateAdd;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment", inversedBy="equipmentmeta", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     * })
     */
    protected $equipment;

    public function __toString() {
        return $this->value;
    }
    
    /**
     * Get equipmentmetaId
     *
     * @return integer 
     */
    public function getEquipmentmetaId()
    {
        return $this->equipmentmetaId;
    }

    /**
     * Set key
     *
     * @param string $key
     * @return Equipmentmeta
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Equipmentmeta
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set dateAdd
     *
     * @param \DateTime $dateAdd
     * @return Equipmentmeta
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
    
        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return \DateTime 
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return Equipmentmeta
     */
    public function setEquipment(\Equipment\Entity\Equipment $equipment = null)
    {
        $this->equipment = $equipment;
    
        return $this;
    }

    /**
     * Get equipment
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getEquipment()
    {
        return $this->equipment;
    }
}
