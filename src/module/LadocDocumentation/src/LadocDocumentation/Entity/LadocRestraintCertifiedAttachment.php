<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LadocRestraintCertifiedAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_restraint_certified_attachment")
 */
class LadocRestraintCertifiedAttachment extends PointAttachment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="point_attachment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $pointAttachmentId;

    /**
     * @var LadocRestraintCertified
     *
     * @ORM\ManyToOne(targetEntity="LadocRestraintCertified", inversedBy="ladocRestraintCertifiedAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_restraint_certified_id", referencedColumnName="id")
     * })
     */
    private $ladocRestraintCertified;

    /**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    protected $description;

    /**
     * Set load restraint documentation for certified carriers
     *
     * @param LadocRestraintCertified $ladocRestraintCertified
     * @return LadocRestraintCertifiedAttachment
     */
    public function setLadocRestraintCertified(LadocRestraintCertified $ladocRestraintCertified = null)
    {
        $this->ladocRestraintCertified = $ladocRestraintCertified;

        return $this;
    }

    /**
     * Get load restraint documentation for certified carriers
     *
     * @return LadocRestraintCertified
     */
    public function getLadocRestraintCertified()
    {
        return $this->ladocRestraintCertified;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTitle($title)
    {
        if(strlen($title) > 15)
            $this->title = substr($title, 0, 15) . '...';
        else
            $this->title = $title;
    }

}
