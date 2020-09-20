<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/31/15
 * Time: 10:41
 */

namespace Application\Service\Cache;


class LocationTaxonomyCache {
    private $locationTaxonomyId;
    private $parentId;
    private $slug;
    private $application;

    /**
     * @return mixed
     */
    public function getLocationTaxonomyId()
    {
        return $this->locationTaxonomyId;
    }

    /**
     * @param mixed $locationTaxonomyId
     */
    public function setLocationTaxonomyId($locationTaxonomyId)
    {
        $this->locationTaxonomyId = $locationTaxonomyId;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param mixed $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }
}