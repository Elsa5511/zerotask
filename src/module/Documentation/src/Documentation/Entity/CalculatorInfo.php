<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acl\Entity\AbstractEntity;

/**
 * Page
 *
 * @ORM\Table(name="calculator_info")
 * @ORM\Entity
 *
 */
class CalculatorInfo extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="calculator_info_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $calculatorInfoId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    protected $link;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity="Documentation\Entity\CalculatorAttachment", mappedBy="calculatorInfo")
     */
    private $attachments;


    public function getCalculatorInfo()
    {
        return $this->calculatorInfoId;
    }

    public function setPageId($calculatorInfoId)
    {
        $this->calculatorInfoId = $calculatorInfoId;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getFormattedDescription()
    {
        return nl2br($this->description);
    }

    public function getAttachments() {
        return $this->attachments;
    }

    public function __toString()
    {
        return $this->getFormattedDescription();
    }

}

