<?php
namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\AttachmentWithLink;

/**
 * Page section attachment
 *
 * @ORM\Table(name="page_section_attachment")
 * @ORM\Entity
 */
class PageSectionAttachment extends AttachmentWithLink
{
   
    /**
     * @var \Documentation\Entity\PageSection
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\PageSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_section_id", referencedColumnName="section_id")
     * })
     */
    private $pageSection;


 /**
     * Set pageSection
     *
     * @param \Documentation\Entity\PageSection $pageSection
     * @return pageSection
     */
    public function setPageSection(\Documentation\Entity\PageSection $pageSection = null)
    {
        $this->pageSection = $pageSection;
    
        return $this;
    }

    /**
     * Get pageSection
     *
     * @return \Documentation\Entity\PageSection
     */
    public function getPageSection()
    {
        return $this->pageSection;
    }
}
