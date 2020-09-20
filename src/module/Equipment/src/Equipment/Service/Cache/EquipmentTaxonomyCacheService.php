<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/21/15
 * Time: 09:49
 */

namespace Equipment\Service\Cache;

use Application\Service\AbstractBaseService;
use Equipment\Repository\EquipmentTaxonomy;


class EquipmentTaxonomyCacheService extends AbstractBaseService {
    const EQUIPMENT_TAXONOMY_CACHE_KEY = 'equipment-taxonomy-cache';

    public function getData() {
        $cache = $this->getCacheAdapter();
        if(!$cache->hasItem(self::EQUIPMENT_TAXONOMY_CACHE_KEY))
            $this->loadCacheData();

        $data = $cache->getItem(self::EQUIPMENT_TAXONOMY_CACHE_KEY);
        if($data == null)   $this->loadCacheData();

        return $data;
    }

    /**
     * Load cache data (Equipment taxonomies) from database
     */
    public function loadCacheData() {
        $entityRepository = $this->getEntityRepository();
        $categories = $entityRepository->getCategoriesForEveryApplication();

        $categoriesToCache = array();
        if($categories) {
            foreach ($categories as $category) {
                $categoryToCache = new EquipmentTaxonomyCache();
                $categoryToCache->setEquipmentTaxonomyId($category->getEquipmentTaxonomyId());
                $categoryToCache->setParentId($category->getParentId());
                $categoryToCache->setApplication($category->getApplication());

                $categoriesToCache[] = $categoryToCache;
            }
        }

        $this->getCacheAdapter()->setItem(self::EQUIPMENT_TAXONOMY_CACHE_KEY, $categoriesToCache);
    }

    public function getChildrenRecursive(\Equipment\Service\Cache\EquipmentTaxonomyCache $categoryCache) {
        $directChildren = $this->getChildren($categoryCache->getEquipmentTaxonomyId());

        $recursiveChildren = $directChildren;
        if ($directChildren !== null) {
            foreach ($directChildren as $child) {
                $recursiveChildren = array_merge($this->getChildrenRecursive($child), $recursiveChildren);
            }
        }
        return $recursiveChildren;
    }

    public function getChildren($equipmentTaxonomyId) {
        $categoriesCache = $this->getData();
        $children = array();
        foreach($categoriesCache as $category)
            if($category->getParentId() == $equipmentTaxonomyId)
                $children[] = $category;

        return $children;
    }

    /**
     * @param array $categoryCacheArray
     * @return array
     */
    public function extractIds($categoryCacheArray) {
        $ids = array();
        foreach ($categoryCacheArray as $category) {
            array_push($ids, $category->getEquipmentTaxonomyId());
        }
        return $ids;
    }

    public function getCacheAdapter() {
        return $this->getDependency('cache');
    }

    /**
     * @return \Equipment\Repository\EquipmentTaxonomy
     */
    protected function getEntityRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentTaxonomy');
    }
}
