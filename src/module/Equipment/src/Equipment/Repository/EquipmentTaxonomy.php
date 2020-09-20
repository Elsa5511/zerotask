<?php

namespace Equipment\Repository;

use Acl\Repository\EntityRepository;

class EquipmentTaxonomy extends EntityRepository
{
    public function getCategoriesForEveryApplication() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('c')
            ->from($this->getEntityName(), 'c')
            ->andWhere("c.status = :status")
            ->setParameter("status", 'active');

        return $queryBuilder->getQuery()->getResult();
    }

    public function fetchParentCategories($equipmentTaxonomyId)
    {
        if(empty($equipmentTaxonomyId)) {
            $criteria = array('status' => 'active');
            $parentCategories = $this->findBy($criteria);
        } else {
            $parentCategories = $this->getFilteredCategories($equipmentTaxonomyId);
        }
        return $parentCategories;
    }

    private function getFilteredCategories($equipmentTaxonomyId) {
        $result = array();
        $currentTaxonomy = $this->find($equipmentTaxonomyId);

        if($currentTaxonomy) {
            $dql = "SELECT et FROM Equipment\Entity\EquipmentTaxonomy  et
                WHERE et.equipmentTaxonomyId != :id AND
                    et.level <= (:level) AND
                    et.status ='active' AND
                    et.application = (:application)";

            $query = $this->getEntityManager()->createQuery($dql);
            $query->setParameter('id', $equipmentTaxonomyId)
                ->setParameter('level', $currentTaxonomy->getLevel())
                ->setParameter('application', $this->getApplication());

            $result = $query->getResult();
        }
        return $result;
    }
    
    public function fetchPotentialChildren($equipmentTaxonomyId)
    {
        $dql = "SELECT et FROM Equipment\Entity\EquipmentTaxonomy et
                WHERE et.parent != (:parentId)
                AND et.equipmentTaxonomyId != (:equipmentTaxonomyId)
                AND et.status ='active'
                AND et.application = (:application)";

        $query = $this->getEntityManager()->createQuery($dql);
        $query
                ->setParameter('parentId', $equipmentTaxonomyId)
                ->setParameter('equipmentTaxonomyId', $equipmentTaxonomyId)
                ->setParameter('application', $this->getApplication());

        $result = $query->getResult();
        return $result;
    }

    public function getActive($criteria = array()) {
        $criteria['status'] = 'active';
        return $this->findBy($criteria);
    }

    /**
     * 
     * @param int $equipmentTaxonomyId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($equipmentTaxonomyId, $translator)
    {        
        $children = $this->getChildren($equipmentTaxonomyId);
        if(count($children) > 0) {
            $resultKey = $translator->translate("Subcategories");
            $children = array($resultKey => $children);
        }

        $equipments = $this->getEquipments($equipmentTaxonomyId);
        if(count($equipments) > 0) {
            $resultKey = $translator->translate("Equipments");
            $equipments = array($resultKey => $equipments);
        }
        
        $pages = $this->getPages($equipmentTaxonomyId);
        if(count($pages) > 0) {
            $resultKey = $translator->translate("Pages");
            $pages = array($resultKey => $pages);
        }

        $result = array_merge($children, $equipments, $pages);
        return $result;
    }
    
    /**
     * 
     * @param int $equipmentTaxonomyId
     * @return array
     */
    public function getChildren($equipmentTaxonomyId)
    {
        $children = $this->findBy(array('parent' => $equipmentTaxonomyId));
        return $children;
    }
    
    private function getEquipments($equipmentTaxonomyId)
    {
        $equipmentTaxonomy = $this->find($equipmentTaxonomyId);
        return $equipmentTaxonomy->getEquipment()->toArray();
    }

    private function getPages($equipmentTaxonomyId)
    {
        $pages = $this->getPageRepository()->findBy(array('category' => $equipmentTaxonomyId));
        return $pages;
    }
    
    private function getPageRepository()
    {
        return $this->getEntityManager()
                ->getRepository('Documentation\Entity\Page');
    }
}
