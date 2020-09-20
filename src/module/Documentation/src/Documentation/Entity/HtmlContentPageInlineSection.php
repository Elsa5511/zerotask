<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Documentation\Entity\BaseHtmlContent;

/**

 * @ORM\Table(name="html_content_page_inline_section")
 * @ORM\Entity
 */
class HtmlContentPageInlineSection extends BaseHtmlContent
{

    /**
     * @var \Documentation\Entity\PageInlineSection
     *
     * @ORM\OneToOne(targetEntity="Documentation\Entity\PageInlineSection")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_inline_section_id", referencedColumnName="section_id",onDelete="CASCADE")
     * })
     */
    protected $pageInlineSection;

    public function getPageInlineSection()
    {
        return $this->pageInlineSection;
    }

    public function setPageInlineSection(\Documentation\Entity\PageInlineSection $pageInlineSection)
    {
        $this->pageInlineSection = $pageInlineSection;
    }

}

