<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Section;
/**
 * DocumentationSection
 *
 * @ORM\Table(name="documentation_section")
 * @ORM\Entity(repositoryClass="Documentation\Repository\DocumentationSectionRepository")
 */
class DocumentationSection extends Section
{

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id")
     * })
     */
    private $equipment;

    /**
     * @ORM\ManyToOne(targetEntity="Documentation\Entity\DocumentationSection", inversedBy="children")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="section_id")
     * })
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\DocumentationSection", mappedBy="parent")
     * @ORM\OrderBy({"sectionOrder" = "ASC"})
     */
    protected $children;
    
    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\DocumentationSectionAttachment", mappedBy="documentationSection")
     */
    private $documentationSectionAttachments;
    
    /**
     * @ORM\OneToOne(targetEntity="Documentation\Entity\HtmlContentDocumentationSection", mappedBy="documentationSection")
     */
    private $htmlContent;
    
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set equipment
     *
     * @param \Equipment\Entity\Equipment $equipment
     * @return EquipmentAttachment
     */
    public function setEquipment(\Equipment\Entity\Equipment $equipment = null)
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * Get equipment
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Get owner
     *
     * @return \Equipment\Entity\Equipment 
     */
    public function getOwner()
    {
        return $this->getEquipment();
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
    public function addChild(\Documentation\Entity\DocumentationSection $child)
    {
        $this->children->add($child);
    }
    
    public function getHtmlContent() {
        return $this->htmlContent;
    }
    
    public function getDocumentationSectionAttachments() {
        return $this->documentationSectionAttachments;
    }
}

