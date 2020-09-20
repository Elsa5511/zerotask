<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Section;
/**
 * PageSection
 *
 * @ORM\Table(name="page_section")
 * @ORM\Entity(repositoryClass="Documentation\Repository\PageSectionRepository")
 */
class PageSection extends Section
{

    /**
     * @var \Documentation\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\Page")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_id", referencedColumnName="page_id")
     * })
     */
    protected $page;

    /**
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\PageSection", inversedBy="children")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="section_id")
     * })
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\PageSection", mappedBy="parent")
     * @ORM\OrderBy({"sectionOrder" = "ASC"})
     */
    protected $children;
    
    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\PageSectionAttachment", mappedBy="pageSection")
     */
    private $pageSectionAttachments;
    
    /**
     * @ORM\OneToOne(targetEntity="Documentation\Entity\HtmlContentPageSection", mappedBy="pageSection")
     */
    private $htmlContent;
    
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set equipment
     *
     * @param \Documentation\Entity\Page $page
     * @return Page
     */
    public function setPage(\Documentation\Entity\Page $page= null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \Documentation\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Get owner
     *
     * @return \Documentation\Entity\Page 
     */
    public function getOwner()
    {
        return $this->getPage();
    }

    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Add canChildren
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $children
     * 
     */
    public function addChild(\Documentation\Entity\PageSection $child)
    {
        $this->children->add($child);
    }
    
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
    public function getPageSectionAttachments() {
        return $this->pageSectionAttachments;
    }
}

