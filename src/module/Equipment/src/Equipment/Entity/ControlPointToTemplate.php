<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sysco\Aurora\Doctrine\ORM\Entity;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_point_to_template")
 */
class ControlPointToTemplate extends Entity {

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var ControlPoint
     *
     * @ORM\ManyToOne(targetEntity="ControlPoint")
     * @ORM\JoinColumn(name="control_point_id", referencedColumnName="control_point_id")
     */
    protected $controlPoint;

    /**
     * @var ControlTemplate
     *
     * @ORM\ManyToOne(targetEntity="ControlTemplate")
     * @ORM\JoinColumn(name="control_template_id", referencedColumnName="control_template_id")
     */
    protected $controlTemplate;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $order;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return ControlPoint
     */
    public function getControlPoint() {
        return $this->controlPoint;
    }

    /**
     * @param ControlPoint $controlPoint
     */
    public function setControlPoint($controlPoint) {
        $this->controlPoint = $controlPoint;
    }

    /**
     * @return ControlTemplate
     */
    public function getControlTemplate() {
        return $this->controlTemplate;
    }

    /**
     * @param ControlTemplate $controlTemplate
     */
    public function setControlTemplate($controlTemplate) {
        $this->controlTemplate = $controlTemplate;
    }

    /**
     * @return int
     */
    public function getOrder() {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order) {
        $this->order = $order;
    }
}