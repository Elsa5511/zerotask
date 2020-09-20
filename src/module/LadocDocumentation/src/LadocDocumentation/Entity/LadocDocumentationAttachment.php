<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoadDocumentationAttachment
 *
 * @ORM\Entity
 * @ORM\Table(name="ladoc_documentation_attachment")
 */
class LadocDocumentationAttachment extends PointAttachment
{
	/**
     * @var string
     * 
     * @ORM\Column(name="description", type="string", nullable=false)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="LadocDocumentation", inversedBy="documentationAttachments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ladoc_documentation_id", referencedColumnName="id")
     * })
     */
    protected $ladocDocumentation;

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setTitle($title)
    {
        if(strlen($title) > 15)
            $this->title = substr($title, 0, 15) . '...';
        else
            $this->title = $title;
    }

    public function getLadocDocumentation() {
        return $this->ladocDocumentation;
    }

    public function setLadocDocumentation($ladocDocumentation) {
        $this->ladocDocumentation = $ladocDocumentation;
    }
}