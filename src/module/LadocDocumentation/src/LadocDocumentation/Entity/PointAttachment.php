<?php

namespace LadocDocumentation\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PointAttachment
 * @ORM\MappedSuperclass
 */
class PointAttachment {
	/**
     * @var integer
     *
     * @ORM\Column(name="point_attachment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $pointAttachmentId;

    /**
     * @var string
     * 
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * @var string
     * 
     * @ORM\Column(name="file", type="string", nullable=false)
     */
    protected $file;

    public function __toString() {
        return $this->title;
    }

    public function setPointAttachmentId($pointAttachmentId)
    {
        $this->pointAttachmentId = $pointAttachmentId;
    }

    public function getPointAttachmentId()
    {
        return $this->pointAttachmentId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getExtension() {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);
        return strtolower($extension);
    }
}