<?php
namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;

/**
 * PageInlineSectionAttachment
 * 
 * 
 *
 * @ORM\Table(name="page_inline_section_attachment")
 * @ORM\Entity
 */
class PageInlineSectionAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Documentation\Entity\PageInlineSection
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\PageInlineSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_inline_section_id", referencedColumnName="section_id")
     * })
     */
    private $pageInlineSection;


 /**
     * Set pageInlineSection
     *
     * @param \Documentation\Entity\PageInlineSection $pageInlineSection
     * @return EquipmentInstanceAttachment
     */
    public function setPageInlineSection(\Documentation\Entity\PageInlineSection $pageInlineSection = null)
    {
        $this->pageInlineSection = $pageInlineSection;
    
        return $this;
    }

    /**
     * Get pageInlineSection
     *
     * @return \Documentation\Entity\PageInlineSection
     */
    public function getPageInlineSection()
    {
        return $this->pageInlineSection;
    }
}
