<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\BaseTaxonomy;

/**
 * LocationTaxonomy
 *
 * @ORM\Table(name="location_taxonomy")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\LocationRepository")
 */
class LocationTaxonomy extends BaseTaxonomy implements \Acl\Entity\AclEntity {

    /**
     * The application of the entity
     * @ORM\Column(type="string")
     */
    protected $application;


    /**
     * @var integer
     *
     * @ORM\Column(name="location_taxonomy_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $locationTaxonomyId;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\LocationTaxonomy")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="location_taxonomy_id")
     * })
     */
    protected $parent;
    
    /**
     * Get taxonomyId
     *
     * @return integer
     */
    public function getLocationTaxonomyId()
    {
        return $this->locationTaxonomyId;
    }
    
    public function setLocationTaxonomyId($taxonomyId)
    {
        $this->locationTaxonomyId = $taxonomyId;
        return $this;
    }

    /**
     *
     * @param LocationTaxonomy $parent            
     * @return LocationTaxonomy
     */
    public function setParent(LocationTaxonomy $parent) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     * 
     * @return LocationTaxonomy
     */
    public function getParent() {
        return $this->parent;
    }
    
    public function __toString()
    {
        return (string) $this->slug;
    }

    /**
     * Return the application in the entity
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the application in the Entity
     * @param string $application
     * @return \Acl\Entity\AbstractEntity
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }
}
