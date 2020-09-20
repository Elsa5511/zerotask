<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;

/**
 * EquipmentTaxonomy
 *
 * @ORM\Table(name="equipment_taxonomy")
 * @ORM\Entity(repositoryClass="Equipment\Repository\EquipmentTaxonomy")
 */
class EquipmentTaxonomy extends BaseTaxonomy implements \Acl\Entity\AclEntity {

    /**
     * The application of the entity
     * @ORM\Column(type="string")
     */
    protected $application;

    /**
     * @var integer
     *
     * @ORM\Column(name="equipment_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $equipmentTaxonomyId;

    /**
     * @ORM\ManyToOne(targetEntity="EquipmentTaxonomy", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="equipment_taxonomy_id")     
     */
    protected $parent;
    protected $parentId;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer", length=45, nullable=true)
     */
    protected $level = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="featured_image", type="string", length=200, nullable=true)
     */
    protected $featuredImage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Equipment\Entity\Equipment", mappedBy="equipmentTaxonomy")
     * @ORM\OrderBy({"title" = "ASC"})
     */
    protected $equipment;

    /**
     * @ORM\ManyToOne(targetEntity="ControlTemplate")
     * @ORM\JoinColumn(name="control_template_id", referencedColumnName="control_template_id")
     * */
    private $controlTemplate;

    /**
     * @ORM\ManyToOne(targetEntity = "Equipment\Entity\CompetenceAreaTaxonomy")
     * @ORM\JoinColumn(name="competence_area_taxonomy_id", referencedColumnName="competence_area_taxonomy_id", nullable=true)
     */
    protected $competenceAreaTaxonomy;

    /**
     * @var integer
     *
     * @ORM\Column(name="template_type", type="integer", nullable=true)
     */
    protected $templateType;

    /**
     * Constructor
     */
    public function __construct() {
        $this->equipment = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get equipmentTaxonomyId
     *
     * @return integer 
     */
    public function getEquipmentTaxonomyId() {
        return $this->equipmentTaxonomyId;
    }

    public function setEquipmentTaxonomyId($equipmentTaxonomyId) {
        $this->equipmentTaxonomyId = $equipmentTaxonomyId;
    }

    /**
     * Add equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return EquipmentTaxonomy
     */
    public function addEquipment(\Equipment\Entity\Equipment $equipment) {
        $this->equipment[] = $equipment;

        return $this;
    }

    /**
     * Remove equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     */
    public function removeEquipment(\Equipment\Entity\Equipment $equipment) {
        $this->equipment->removeElement($equipment);
    }

    /**
     * Get equipment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEquipment() {
        return $this->equipment;
    }

    public function getActiveEquipments() {
        $activeEquipments = array();
        foreach ($this->equipment as $equipment) {
            if ($equipment->getStatus() === 'active') {
                array_push($activeEquipments, $equipment);
            }
        }
        return $activeEquipments;
    }


    /**
     * Get featuredImage
     * 
     * @return string
     */
    public function getFeaturedImage() {
        return $this->featuredImage;
    }

    /**
     * Set featuredImage
     *
     * @param string $dateUpdate
     * @return Equipment
     */
    public function setFeaturedImage($featuredImage) {
        $this->featuredImage = $featuredImage;
    }

    public function getControlTemplate() {
        return $this->controlTemplate;
    }

    public function setControlTemplate($controlTemplate) {
        $this->controlTemplate = $controlTemplate;
    }

    public function getCompetenceAreaTaxonomy() {
        return $this->competenceAreaTaxonomy;
    }

    public function setCompetenceAreaTaxonomy($competenceAreaTaxonomy) {
        $this->competenceAreaTaxonomy = $competenceAreaTaxonomy;
    }

    /**
     * Set parentId
     *
     * @param integer $parent
     * @return EquipmentTaxonomy
     */
    public function setParent($parent) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Get parentId
     *
     * @return integer 
     */
    public function getParentId() {
        $parentId = 0;
        $parent = $this->getParent();
        if ($parent) {
            $parentId = $parent->getEquipmentTaxonomyId();
        }
        return $parentId;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function setLevel($level = null) {
        if (is_null($level)) {
            $parent = $this->getParent();
            if ($parent) {
                $newLevel = $parent->getLevel() + 1;
            } else {
                $newLevel = 1;
            }
            $this->level = $newLevel;
        } else {
            $this->level = $level;
        }
    }

    /**
     * Get Template Type
     *
     * @return integer 
     */
    public function getTemplateType() {
        return $this->templateType;
    }

    public function getClosestTemplateType() {
        $obj = $this;
        $templateType = $obj->getTemplateType();
        while (!$templateType && $obj->getParent()) {
            $obj = $obj->getParent();
            $templateType = $obj->getTemplateType();
        }

        return $templateType ? $templateType : EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD;
    }

    /**
     * Get Template Type
     *
     * @return integer 
     */
    public function setTemplateType($templateType = null) {
        $this->templateType = $templateType;
    }

    /**
     * Return the application in the entity
     * @return string
     */
    public function getApplication() {
        return $this->application;
    }

    /**
     * Set the application in the Entity
     * @param string $application
     * @return \Acl\Entity\AbstractEntity
     */
    public function setApplication($application) {
        $this->application = $application;
        return $this;
    }

    public function setActiveStatus($activeStatus) {
        $this->setStatus($activeStatus);
    }

    public function getActiveStatus() {
        return $this->getStatus();
    }

}
