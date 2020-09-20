<?php

namespace Equipment\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;

/**
 * 
 * @ORM\Table(name="competence_area_taxonomy")
 * @ORM\Entity
 */
class CompetenceAreaTaxonomy extends BaseTaxonomy {

    /**
     * @var integer
     * 
     * @ORM\Column(name = "competence_area_taxonomy_id", type = "integer", nullable = false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $competenceAreaTaxonomyId;

    public function getCompetenceAreaTaxonomyId() {
        return $this->competenceAreaTaxonomyId;
    }

    public function setCompetenceAreaTaxonomyId($competenceAreaTaxonomyId) {
        $this->competenceAreaTaxonomyId = $competenceAreaTaxonomyId;
    }

}
