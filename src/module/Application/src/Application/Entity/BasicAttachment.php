<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This entity was created to provide a simpler setup than the Attachment entity,
 * which has a lot of fields that are not normally used.
 *
 * @ORM\MappedSuperclass
 */
class BasicAttachment {
    /**
     * @ORM\Column(name="point_attachment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(name="file", type="string", nullable=false)
     */
    protected $file;

    public function __toString() {
        return $this->title;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getExtension() {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);
        return strtolower($extension);
    }
}