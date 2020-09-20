<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipmentStock
 *
 * @ORM\Table(name="equipment_stock")
 * @ORM\Entity
 */
class EquipmentStock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="equipment_stock_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $equipmentStockId;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="date_add", type="string", length=45, nullable=false)
     */
    private $dateAdd;

    /**
     * @var string
     *
     * @ORM\Column(name="date_update", type="string", length=45, nullable=false)
     */
    private $dateUpdate;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     * })
     */
    private $equipment;



    /**
     * Get equipmentStockId
     *
     * @return integer 
     */
    public function getEquipmentStockId()
    {
        return $this->equipmentStockId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return EquipmentStock
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set dateAdd
     *
     * @param string $dateAdd
     * @return EquipmentStock
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;
    
        return $this;
    }

    /**
     * Get dateAdd
     *
     * @return string 
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Set dateUpdate
     *
     * @param string $dateUpdate
     * @return EquipmentStock
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;
    
        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return string 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return EquipmentStock
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
