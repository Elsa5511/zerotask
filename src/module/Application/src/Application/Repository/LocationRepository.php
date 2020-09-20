<?php

namespace Application\Repository;

use Acl\Repository\EntityRepository;
use Application\Service\LocationService;

class LocationRepository extends EntityRepository
{

    public function findFirstLevelChildren($locationId) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->from($this->getEntityName(), 'l');
        $queryBuilder->select('l');
        $queryBuilder->andWhere('l.parent = (:parentId)');
        $queryBuilder->setParameter('parentId', $locationId);
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

    public function getEntitiesRelated($locationId)
    {
        $dqlForEquipment =
        "SELECT CONCAT('Equipment instance: ', eq.serialNumber,' (Serial number)') AS " . LocationService::ALIAS_KEY_RELATIONSHIPS . " 
            FROM Equipment\Entity\EquipmentInstance eq
            WHERE eq.location = (:locationId)";
        $query = $this->getEntityManager()->createQuery($dqlForEquipment);
        $query->setParameter('locationId', $locationId);
        $equipments = $query->getResult();
        
        $dqlForLocation =
        "SELECT CONCAT('Location: ', l.slug) AS " . LocationService::ALIAS_KEY_RELATIONSHIPS . "
            FROM Application\Entity\LocationTaxonomy l
            WHERE l.parent = (:locationId)";
        $query = $this->getEntityManager()->createQuery($dqlForLocation);
        $query->setParameter('locationId', $locationId);
        $locations = $query->getResult();

        $result = array_merge($equipments,$locations);
        return $result;
    }

    public function getLocationsForEveryApplication() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->select('l')
            ->from('Application\Entity\LocationTaxonomy', 'l')
            ->orderBy("l.slug");
        return $queryBuilder->getQuery()->getResult();
    }
}
