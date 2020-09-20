<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BasicAttachment;

/**
 * Page
 *
 * @ORM\Table(name="calculator_attachment")
 * @ORM\Entity
 *
 */
class CalculatorAttachment extends BasicAttachment
{

    /**
     * @var \Documentation\Entity\CalculatorInfo
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\CalculatorInfo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calculator_info_id", referencedColumnName="calculator_info_id")
     * })
     */
    private $calculatorInfo;


    /**
     * Set calculatorInfo
     *
     * @param \Documentation\Entity\CalculatorInfo $calculatorInfo
     * @return CalculatorAttachment
     */
    public function setCalculatorInfo(\Documentation\Entity\CalculatorInfo $calculatorInfo = null)
    {
        $this->calculatorInfo = $calculatorInfo;
    
        return $this;
    }

    /**
     * Get calculatorInfo
     *
     * @return \Documentation\Entity\CalculatorInfo
     */
    public function getCalculatorInfo()
    {
        return $this->calculatorInfo;
    }

}

