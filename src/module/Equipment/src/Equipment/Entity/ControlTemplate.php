<?php

namespace Equipment\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="control_template")
 */
class ControlTemplate extends Entity {
    /**
     * @var integer
     *
     * @ORM\Column(name="control_template_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $controlTemplateId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="standard_text", type="text", nullable=true)
     */
    protected $standardText;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="ControlPointToTemplate", mappedBy="controlTemplate")
     */
    protected $controlPointsToTemplate;

    public function __construct()
    {
        $this->controlPointsToTemplate = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getControlTemplateId() {
        return $this->controlTemplateId;
    }

    public function setControlTemplateId($controlTemplateId) {
        $this->controlTemplateId = $controlTemplateId;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getStandardText() {
        return $this->standardText;
    }

    public function setStandardText($standardText) {
        $this->standardText = $standardText;
    }

    /**
     * @return ArrayCollection
     */
    public function getControlPointsToTemplate() {
        return $this->controlPointsToTemplate;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderedControlPointsToTemplate() {
        $iterator = $this->controlPointsToTemplate->getIterator();
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


    /**
     * @param ArrayCollection $controlPointsToTemplate
     */
    public function setControlPointsToTemplate($controlPointsToTemplate) {
        $this->controlPointsToTemplate = $controlPointsToTemplate;
    }

    /**
     * @return array
     */
    public function getControlPointsByOrder() {
        $controlPoints = array();

        $iterator = $this->controlPointsToTemplate->getIterator();
        $iterator->uasort(function ($a, $b) {
            if (!$b->getOrder()) {
                return -1;
            }
            if (!$a->getOrder()) {
                return 1;
            }
            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        });
        $sortedControlPointsToTemplate = new ArrayCollection(iterator_to_array($iterator));
        foreach ($sortedControlPointsToTemplate as $controlPointsToTemplate) {
            array_push($controlPoints, $controlPointsToTemplate->getControlPoint());
        }

        return $controlPoints;
    }
}
