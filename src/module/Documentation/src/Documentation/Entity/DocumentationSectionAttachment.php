<?php
namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;

/**
 * Documentation section
 *
 * @ORM\Table(name="documentation_section_attachment")
 * @ORM\Entity
 */
class DocumentationSectionAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Documentation\Entity\DocumentationSection
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\DocumentationSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="documentation_section_id", referencedColumnName="section_id")
     * })
     */
    private $documentationSection;


 /**
     * Set documentationSection
     *
     * @param \Documentation\Entity\DocumentationSection $documentationSection
     * @return EquipmentInstanceAttachment
     */
    public function setDocumentationSection(\Documentation\Entity\DocumentationSection $documentationSection = null)
    {
        $this->documentationSection = $documentationSection;
    
        return $this;
    }

    /**
     * Get documentationSection
     *
     * @return \Documentation\Entity\DocumentationSection
     */
    public function getDocumentationSection()
    {
        return $this->documentationSection;
    }
}
