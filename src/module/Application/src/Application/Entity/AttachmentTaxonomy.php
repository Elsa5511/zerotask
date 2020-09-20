<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;

/**
 * AttachmentTaxonomy
 *
 * @ORM\Table(name="attachment_taxonomy")
 * @ORM\Entity
 */
class AttachmentTaxonomy extends BaseTaxonomy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="attachment_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $attachmentTaxonomyId;
    
    /**
     * Get taxonomyId
     *
     * @return integer
     */
    public function getAttachmentTaxonomyId()
    {
        return $this->attachmentTaxonomyId;
    }
    
    public function setAttachmentTaxonomyId($taxonomyId)
    {
        $this->attachmentTaxonomyId = $taxonomyId;
        return $this;
    }
    
    public function __toString() {
        return $this->type;
    }
    
}
