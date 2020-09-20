<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LadocRestraintCertifiedDocument
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_restraint_certified_document")
 */
class LadocRestraintCertifiedDocument extends PointAttachment
{
    /**
     * @ORM\ManyToOne(targetEntity="LadocRestraintCertified", inversedBy="ladocRestraintCertifiedDocuments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_restraint_certified_id", referencedColumnName="id")
     * })
     */
    protected $ladocRestraintCertified;

    public function getLadocRestraintCertified() {
        return $this->ladocRestraintCertified;
    }

    public function setLadocRestraintCertified($ladocRestraintCertified) {
        $this->ladocRestraintCertified = $ladocRestraintCertified;
    }
}