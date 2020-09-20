<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Documentation\Entity\BaseHtmlContent;

/**

 * @ORM\Table(name="html_content_inline_section")
 * @ORM\Entity
 */
class HtmlContentInlineSection extends BaseHtmlContent
{

    /**
     * @var \Documentation\Entity\InlineSection
     *
     * @ORM\OneToOne(targetEntity="Documentation\Entity\InlineSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inline_section_id", referencedColumnName="section_id",onDelete="CASCADE")
     * })
     */
    protected $inlineSection;
    
    public function getInlineSection()
    {
        return $this->inlineSection;
    }

    public function setInlineSection(\Documentation\Entity\InlineSection $inlineSection)
    {
        $this->inlineSection = $inlineSection;
    }



}

