<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Section;

/**
 * InlineSection
 *
 * @ORM\Table(name="inline_section")
 * @ORM\Entity(repositoryClass="Documentation\Repository\InlineSectionRepository")
 */
class InlineSection extends Section
{

    /**
     * @var \Documentation\Entity\DocumentationSection
     *
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\DocumentationSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="documentation_id", referencedColumnName="section_id")
     * })
     */
    private $documentation;

    /**
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\InlineSection", inversedBy="children")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="section_id")
     * })
     */
    protected $parent;
    
    /**
     * @ORM\OneToOne(targetEntity="Documentation\Entity\HtmlContentInlineSection", mappedBy="inlineSection")
     */
    private $htmlContent;
    
    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\InlineSectionAttachment", mappedBy="inlineSection")
     * 
     */
    private $inlineSectionAttachments;

   
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get owner
     *
     * @return \Documentation\Entity\DocumentationSection 
     */
    public function getOwner()
    {
        return $this->getDocumentation();
    }


    public function getDocumentation()
    {
        return $this->documentation;
    }

    public function setDocumentation($documentation)
    {
        $this->documentation = $documentation;
    }
    
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
    public function getInlineSectionAttachments() {
        return $this->inlineSectionAttachments;
    }
}

