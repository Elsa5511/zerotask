<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/21/15
 * Time: 09:06
 */

namespace Equipment\Service\Cache;


class EquipmentTaxonomyCache {
    private $equipmentTaxonomyId;
    private $parentId;
    private $application;

    public function getEquipmentTaxonomyId(){
        return $this->equipmentTaxonomyId;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function getApplication() {
        return $this->application;
    }

    public function setEquipmentTaxonomyId($equipmentTaxonomyId) {
        $this->equipmentTaxonomyId = $equipmentTaxonomyId;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
    }

    public function setApplication($application) {
        $this->application = $application;
    }
}