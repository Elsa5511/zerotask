<?php

namespace Documentation\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acl\Entity\AbstractEntity;

/**
 * Page
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="Documentation\Repository\PageRepository")
 * 
 */
class Page extends AbstractEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="page_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $pageId;

    /**
     * @var \Equipment\Entity\EquipmentTaxonomy
     *
     * @ORM\ManyToOne(targetEntity="Equipment\Entity\EquipmentTaxonomy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_taxonomy_id", referencedColumnName="equipment_taxonomy_id",nullable=true)
     * })
     */
    protected $category;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="featured_image", type="string", length=200, nullable=true)
     */
    protected $featuredImage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="search_enabled", type="boolean", nullable=false)
     */
    protected $searchEnabled = false;


    public function getPageId()
    {
        return $this->pageId;
    }

    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory(\Equipment\Entity\EquipmentTaxonomy $category)
    {
        $this->category = $category;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Get featuredImage
     * 
     * @return string
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * Set featuredImage
     *
     * @param string $dateUpdate
     * @return Equipment
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;
    }

    /**
     *
     * @return boolean
     */
    public function isSearchEnabled()
    {
        return $this->searchEnabled;
    }

    /**
     *
     * @param boolean $searchEnabled
     */
    public function setSearchEnabled($searchEnabled)
    {
        $this->searchEnabled = $searchEnabled;
    }

    public function __toString()
    {
        return $this->getName();
    }

}

