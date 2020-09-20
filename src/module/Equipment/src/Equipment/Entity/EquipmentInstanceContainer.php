<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Equipment\Repository\EquipmentInstance")
 */
class EquipmentInstanceContainer extends EquipmentInstance {

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_isolated", type="boolean", nullable=false)
     */
    protected $isIsolated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_dryair", type="boolean", nullable=false)
     */
    protected $hasDryair;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_volt_220", type="boolean", nullable=false)
     */
    protected $hasVolt220;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_volt_400", type="boolean", nullable=false)
     */
    protected $hasVolt400;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_communication_racks", type="boolean", nullable=false)
     */
    protected $hasCommunicationRacks;

    /**
     * @var boolean
     *
     * @ORM\Column(name="has_other_decor", type="boolean", nullable=false)
     */
    protected $hasOtherDecor;

    /**
     * @return boolean
     */
    public function isIsIsolated() {
        return $this->isIsolated;
    }

    /**
     * @param boolean $isIsolated
     */
    public function setIsIsolated($isIsolated) {
        $this->isIsolated = $isIsolated;
    }

    /**
     * @return boolean
     */
    public function isHasDryair() {
        return $this->hasDryair;
    }

    /**
     * @param boolean $hasDryair
     */
    public function setHasDryair($hasDryair) {
        $this->hasDryair = $hasDryair;
    }

    /**
     * @return boolean
     */
    public function isHasVolt220() {
        return $this->hasVolt220;
    }

    /**
     * @param boolean $hasVolt220
     */
    public function setHasVolt220($hasVolt220) {
        $this->hasVolt220 = $hasVolt220;
    }

    /**
     * @return boolean
     */
    public function isHasVolt400() {
        return $this->hasVolt400;
    }

    /**
     * @param boolean $hasVolt400
     */
    public function setHasVolt400($hasVolt400) {
        $this->hasVolt400 = $hasVolt400;
    }

    /**
     * @return boolean
     */
    public function isHasCommunicationRacks() {
        return $this->hasCommunicationRacks;
    }

    /**
     * @param boolean $hasCommunicationRacks
     */
    public function setHasCommunicationRacks($hasCommunicationRacks) {
        $this->hasCommunicationRacks = $hasCommunicationRacks;
    }

    /**
     * @return boolean
     */
    public function isHasOtherDecor() {
        return $this->hasOtherDecor;
    }

    /**
     * @param boolean $hasOtherDecor
     */
    public function setHasOtherDecor($hasOtherDecor) {
        $this->hasOtherDecor = $hasOtherDecor;
    }
}