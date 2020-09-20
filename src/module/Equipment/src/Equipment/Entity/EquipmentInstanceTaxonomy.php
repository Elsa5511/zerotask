<?php
namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;

/**
 * EquipmentInstanceTaxonomy
 *
 * @ORM\Table(name="equipment_instance_taxonomy")
 * @ORM\Entity
 */
class EquipmentInstanceTaxonomy extends BaseTaxonomy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="equipment_instance_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $equipmentInstanceTaxonomy;
    
    /**
     * Get taxonomyId
     *
     * @return integer
     */
    public function getEquipmentInstanceTaxonomy()
    {
        return $this->equipmentInstanceTaxonomy;
    }
    
    public function setEquipmentInstanceTaxonomy($taxonomyId)
    {
        $this->equipmentInstanceTaxonomy = $taxonomyId;
        return $this;
    }
    
}
