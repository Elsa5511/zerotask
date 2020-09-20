<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Documentation\Entity\BaseHtmlContent;

/**

 * @ORM\Table(name="html_content_documentation_section")
 * @ORM\Entity
 */
class HtmlContentDocumentationSection extends BaseHtmlContent
{

    /**
     * @var \Documentation\Entity\DocumentationSection
     *
     * @ORM\OneToOne(targetEntity="Documentation\Entity\DocumentationSection", inversedBy="htmlContent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="documentation_section_id", referencedColumnName="section_id",onDelete="CASCADE")
     * })
     */
    protected $documentationSection;
    

    public function getDocumentationSection()
    {
        return $this->documentationSection;
    }

    public function setDocumentationSection(\Documentation\Entity\DocumentationSection $documentationSection)
    {
        $this->documentationSection = $documentationSection;
    }


}

