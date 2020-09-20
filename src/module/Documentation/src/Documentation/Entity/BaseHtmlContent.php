<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sysco\Aurora\Stdlib\DateTime;

/**
 * HtmlContent
 * @ORM\MappedSuperclass
 */
class BaseHtmlContent
{

    /**
     * @var integer
     *
     * @ORM\Column(name="html_content_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $htmlContentId;

  

    /**
     * @var string
     *
     * @ORM\Column(name="html_content", type="text",nullable=true)
     */
    protected $htmlContent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    protected $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=true)
     */
    protected $dateUpdate;

    public function getHtmlContentId()
    {
        return $this->htmlContentId;
    }

    public function setHtmlContentId($htmlContentId)
    {
        $this->htmlContentId = $htmlContentId;
    }

    public function getHtmlContent()
    {
        return $this->htmlContent;
    }

    public function setHtmlContent($htmlContent)
    {
        $this->htmlContent = $htmlContent;
    }

    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = new DateTime($dateAdd);

        return $this;
    }

    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = new DateTime($dateUpdate);

        return $this;
    }
    
    public function __toString() {
        return $this->htmlContent;
    }

}

