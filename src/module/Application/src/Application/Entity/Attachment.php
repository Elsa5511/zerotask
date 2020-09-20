<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attachment
 * @ORM\MappedSuperclass
 * 
 */
class Attachment extends \Acl\Entity\AbstractEntity {

    /**
     * @var \Application\Entity\AttachmentTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\AttachmentTaxonomy",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attachment_taxonomy_id", referencedColumnName="attachment_taxonomy_id")
     * })
     */
    protected $attachmentTaxonomy;

    /**
     * @var integer
     *
     * @ORM\Column(name="attachment_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $attachmentId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=200, nullable=true)
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=200, nullable=true)
     */
    protected $author;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=100, nullable=true)
     */
    protected $version;

    /**
     * @var string
     *
     * @ORM\Column(name="mime_type", type="string", length=100, nullable=true)
     */
    protected $mimeType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime", nullable=true)
     */
    protected $dateAdd;

    /**
     * get uattachmentTaxonomy
     *
     * @param \Application\Entity\AttachmentTaxonomy $attachmentTaxonomy
     * @return AttachmentTaxonomy
     */
    public function getAttachmentTaxonomy() {
        return $this->attachmentTaxonomy;
    }

    /**
     * Set attachmentTaxonomy
     *
     * @param \Application\Entity\AttachmentTaxonomy $attachmentTaxonomy
     * @return AttachmentTaxonomy
     */
    public function setAttachmentTaxonomy(\Application\Entity\AttachmentTaxonomy $attachmentTaxonomy = null) {
        $this->attachmentTaxonomy = $attachmentTaxonomy;
        return $this;
    }

    /**
     * Get attachmentId
     *
     * @return integer 
     */
    public function getAttachmentId() {
        return $this->attachmentId;
    }

    public function setAttachmentId($attachmentId) {
        $this->attachmentId = $attachmentId;
        return $this;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EquipmentInstanceAttachment
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return EquipmentInstanceAttachment
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return EquipmentInstanceAttachment
     */
    public function setFile($file) {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Equipment
     */
    public function setAuthor($author = null) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getAuthor() {
        return $this->author;
    }

    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function getDateAdd() {
        return $this->dateAdd;
    }

    public function setDateAdd(\DateTime $dateAdd) {
        $this->dateAdd = $dateAdd;
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    public function setMimeType($mimeType) {
        $this->mimeType = $mimeType;
    }

    public function getExtension() {
        $extension = pathinfo($this->file, PATHINFO_EXTENSION);
        return strtolower($extension);
    }

}
