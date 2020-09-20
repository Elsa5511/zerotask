<?php

namespace BestPractice\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="best_practice")
 * @ORM\Entity(repositoryClass="BestPractice\Repository\BestPracticeRepository")
 */
class BestPractice extends \Acl\Entity\AbstractEntity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="best_practice_id", type="integer")
     * */
    protected $bestPracticeId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=100, nullable=true)
     */
    protected $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="featured_image", type="string", length=100, nullable=true)
     */
    protected $featuredImage;

    /**
     * @var string
     *
     * @ORM\Column(name="slides", type="simple_array", nullable=true)
     */
    protected $slides;

    /**
     * @var integer
     *
     * @ORM\Column(name="version_id", type="integer", nullable=true)
     */
    protected $versionId = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=50, nullable=true)
     */
    protected $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="revision_date", type="datetime", nullable=true)
     */
    protected $revisionDate;

    /**
     * @var string
     *
     * @ORM\Column(name="revision_number", type="string", length=50, nullable=false)
     */
    protected $revisionNumber;

    /**
     * @var string
     * 
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    protected $comment;

    /**
     * @var \Equipment\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\Equipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="equipment_id", nullable=false)
     * })
     */
    protected $equipment;


    public function __construct() {
        $this->slides = array("", "");
    }

    public function getBestPracticeId() {
        return $this->bestPracticeId;
    }

    public function setBestPracticeId($bestPracticeId) {
        $this->bestPracticeId = $bestPracticeId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getSubtitle() {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle) {
        $this->subtitle = $subtitle;
    }

    public function getFeaturedImage() {
        return $this->featuredImage;
    }

    public function setFeaturedImage($featuredImage) {
        $this->featuredImage = $featuredImage;
    }

    public function getSlides() {
        return $this->slides;
    }

    public function setSlides($slides) {
        $this->slides = $slides;
    }

    public function getVersionId() {
        return $this->versionId;
    }

    public function setVersionId($versionId) {
        $this->versionId = $versionId;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function getRevisionDate() {
        return $this->revisionDate;
    }

    public function setRevisionDate($revisionDate) {
        $this->revisionDate = $revisionDate;
    }

    public function getRevisionNumber() {
        return $this->revisionNumber;
    }

    public function setRevisionNumber($revisionNumber) {
        $this->revisionNumber = $revisionNumber;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getEquipment() {
        return $this->equipment;
    }

    public function setEquipment(\Equipment\Entity\Equipment $equipment) {
        $this->equipment = $equipment;
    }

    public function getValidSlides() {
        $validSlides = array();
        foreach ($this->slides as $slide) {
            if (preg_match("/(\.)(.+)/", $slide)) { // Check if slide has file extension.
                array_push($validSlides, $slide);
            }
        }
        return $validSlides;
    }
    
    public function __clone() {
        if ($this->bestPracticeId) {
            $this->setBestPracticeId(null);
            $versionId = $this->getVersionId() + 1;
            $this->setVersionId($versionId);
        }
    }

}
