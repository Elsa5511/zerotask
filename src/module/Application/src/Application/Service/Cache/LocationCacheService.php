<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/31/15
 * Time: 10:24
 */

namespace Application\Service\Cache;

use Acl\Service\AbstractService;
use Application\Service\Cache\LocationTaxonomyCache;

class LocationCacheService extends AbstractService {
    const LOCATIONS_CACHE_KEY = 'locations-cache';

    public function getData() {
        $cache = $this->getCacheAdapter();
        if(!$cache->hasItem(self::LOCATIONS_CACHE_KEY))
            $this->loadCacheData();

        $data = $cache->getItem(self::LOCATIONS_CACHE_KEY);
        if($data == null)   $this->loadCacheData();

        return $data;
    }

    /**
     * Load cache data (Location taxonomies) from database
     */
    public function loadCacheData() {
        $entityRepository = $this->getEntityRepository();
        $locations = $entityRepository->getLocationsForEveryApplication();

        $locationsToCache = array();
        if($locations) {
            foreach ($locations as $location) {
                $locationToCache = new LocationTaxonomyCache();
                $locationToCache->setLocationTaxonomyId($location->getLocationTaxonomyId());
                if($location->getParent())
                    $locationToCache->setParentId($location->getParent()->getLocationTaxonomyId());
                $locationToCache->setSlug($location->getSlug());
                $locationToCache->setApplication($location->getApplication());

                $locationsToCache[] = $locationToCache;
            }
        }

        $this->getCacheAdapter()->setItem(self::LOCATIONS_CACHE_KEY, $locationsToCache);
    }

    public function searchValuesByApplicationAndSlug($application, $slug = "") {
        $locations = $this->getData();

        $filteredLocations = array();

        if($locations) {
            foreach($locations as $location) {
                $slugConditional = true;
                if(strlen($slug) > 0)
                    $slugConditional = strpos(strtolower($location->getSlug()), strtolower($slug)) !== FALSE;

                if ($location->getApplication() == strtolower($application) && $slugConditional)
                    $filteredLocations[] = array(
                        "value" => $location->getLocationTaxonomyId(),
                        "text" => $location->getSlug()
                    );
            }
        }

        return $filteredLocations;
    }

    public function getCacheAdapter() {
        return $this->getDependency('cache');
    }

    /**
     * @return \Application\Repository\LocationRepository
     */
    protected function getEntityRepository() {
        return $this->getRepository('Application\Entity\LocationTaxonomy');
    }
}