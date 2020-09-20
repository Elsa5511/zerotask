<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;

/**
 * PeriodicControlTaxonomy
 *
 * @ORM\Table(name="periodic_control_taxonomy")
 * @ORM\Entity
 * 
 */
class PeriodicControlTaxonomy extends BaseTaxonomy
{

    /**
     * @var integer
     *
     * @ORM\Column(name="periodic_control_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $periodicControlTaxonomyId;

    public function getPeriodicControlTaxonomyId()
    {
        return $this->periodicControlTaxonomyId;
    }

    public function setPeriodicControlTaxonomyId($periodicControlTaxonomyId)
    {
        $this->periodicControlTaxonomyId = $periodicControlTaxonomyId;
    }

}
