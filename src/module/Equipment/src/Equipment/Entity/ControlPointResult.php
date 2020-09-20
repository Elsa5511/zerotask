<?php

namespace Equipment\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_point_result")
 */
class ControlPointResult extends Entity {
    /**
     * @var integer
     *
     * @ORM\Column(name="control_point_result_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $controlPointResultId;
    
    /**
     * TODO: This field was kept to support unordered control points.
     * TODO: After migration it should probably be removed.
     * @var ControlPoint
     *
     * @ORM\ManyToOne(targetEntity="ControlPoint")
     * @ORM\JoinColumn(name="control_point_id", referencedColumnName="control_point_id")
     **/
    protected $controlPoint;

    /**
     * @var ControlPointToTemplate
     *
     * @ORM\ManyToOne(targetEntity="ControlPointToTemplate")
     * @ORM\JoinColumn(name="control_point_to_template_id", referencedColumnName="id")
     */
    protected $controlPointToTemplate;

    
    /**
     * @ORM\ManyToOne(targetEntity="ControlPointOption")
     * @ORM\JoinColumn(name="control_point_option_id", referencedColumnName="control_point_option_id")
     **/
    protected $controlPointOption;
    
    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text", nullable=true)
     */
    protected $remark;

    public function getControlPointResultId() {
        return $this->controlPointResultId;
    }

    public function setControlPointResultId($controlPointResultId) {
        $this->controlPointResultId = $controlPointResultId;
    }

    public function getControlPoint() {
        if ($this->controlPointToTemplate === null) {
            return $this->controlPoint;
        }
        else {
            return $this->controlPointToTemplate->getControlPoint();
        }
    }

    public function setControlPoint($controlPoint) {
        $this->controlPoint = $controlPoint;
    }

    public function getControlPointOption() {
        return $this->controlPointOption;
    }

    public function setControlPointOption($controlPointOption) {
        $this->controlPointOption = $controlPointOption;
    }

    public function getRemark() {
        return $this->remark;
    }

    public function setRemark($remark) {
        $this->remark = $remark;
    }

    /**
     * @return ControlPointToTemplate
     */
    public function getControlPointToTemplate() {
        return $this->controlPointToTemplate;
    }

    /**
     * @param ControlPointToTemplate $controlPointToTemplate
     */
    public function setControlPointToTemplate($controlPointToTemplate) {
        $this->controlPointToTemplate = $controlPointToTemplate;
    }

    /**
     * @return int
     */
    public function getOrder() {
        if ($this->controlPointToTemplate !== null) {
            return $this->controlPointToTemplate->getOrder();
        }
        else {
            return 0;
        }
    }
    
    public function __clone() {
        if ($this->controlPointResultId) {
            $this->setControlPointResultId(null);
        }
    }
}
