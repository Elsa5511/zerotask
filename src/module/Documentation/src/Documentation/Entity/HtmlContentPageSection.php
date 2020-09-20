<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Documentation\Entity\BaseHtmlContent;

/**

 * @ORM\Table(name="html_content_page_section")
 * @ORM\Entity
 */
class HtmlContentPageSection extends BaseHtmlContent
{

    /**
     * @var \Documentation\Entity\PageSection
     *
     * @ORM\OneToOne(targetEntity="Documentation\Entity\PageSection", inversedBy="htmlContent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="page_section_id", referencedColumnName="section_id",onDelete="CASCADE")
     * })
     */
    protected $pageSection;
    

    public function getPageSection()
    {
        return $this->pageSection;
    }

    public function setPageSection(\Documentation\Entity\PageSection $pageSection)
    {
        $this->pageSection = $pageSection;
    }


}

