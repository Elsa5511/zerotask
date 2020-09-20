<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Section;

/**
 * InlineSection
 *
 * @ORM\Table(name="page_inline_section")
 * @ORM\Entity(repositoryClass="Documentation\Repository\PageInlineSectionRepository")
 */
class PageInlineSection extends Section
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
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\PageInlineSection", inversedBy="children")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="section_id")
     * })
     */
    protected $parent;
    
    /**
     * @ORM\OneToOne(targetEntity="Documentation\Entity\HtmlContentPageInlineSection", mappedBy="pageInlineSection")
     */
    private $htmlContent;
    
    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\PageInlineSectionAttachment", mappedBy="pageInlineSection")
     * 
     */
    private $pageInlineSectionAttachments;

   
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get owner
     *
     * @return \Documentation\Entity\PageSection 
     */
    public function getOwner()
    {
        return $this->getPageSection();
    }


    public function getPageSection()
    {
        return $this->pageSection;
    }

    public function setPageSection($pageSection)
    {
        $this->pageSection = $pageSection;
    }
    
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
    public function getPageInlineSectionAttachments() {
        return $this->pageInlineSectionAttachments;
    }
    
}

