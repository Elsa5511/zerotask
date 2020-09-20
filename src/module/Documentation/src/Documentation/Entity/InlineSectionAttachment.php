<?php
namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;

/**
 * Documentation section
 *
 * @ORM\Table(name="inline_section_attachment")
 * @ORM\Entity
 */
class InlineSectionAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Documentation\Entity\InlineSection
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\InlineSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inline_section_id", referencedColumnName="section_id")
     * })
     */
    private $inlineSection;


 /**
     * Set inlineSection
     *
     * @param \Documentation\Entity\InlineSection $inlineSection
     * @return EquipmentInstanceAttachment
     */
    public function setInlineSection(\Documentation\Entity\InlineSection $inlineSection = null)
    {
        $this->inlineSection = $inlineSection;
    
        return $this;
    }

    /**
     * Get inlineSection
     *
     * @return \Documentation\Entity\InlineSection
     */
    public function getInlineSection()
    {
        return $this->inlineSection;
    }
}
