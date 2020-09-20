<?php

namespace Equipment\Repository;

use Application\Constants\StatusConstants;
use Equipment\Entity\InstanceExpirationFieldTypes;
use Equipment\Service\EquipmentInstanceService;
use Doctrine\ORM\Query\ResultSetMapping;

class EquipmentInstance extends \Acl\Repository\EntityRepository {

    public function getEquipmentInstanceSearchCount(array $criteria, array $locationIds, $includeInactive) {
        $qb = $this->getEquipmentInstancesSearchQueryBody($criteria, $locationIds, $includeInactive);
        $qb->select("count(ei)");

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getEquipmentInstancesSearch(array $criteria, array $locationIds, $includeInactive) {
        $qb = $this->getEquipmentInstancesSearchQueryBody($criteria, $locationIds, $includeInactive);
        $qb->select("ei");

        return $qb->getQuery()->getResult();
    }

    private function getEquipmentInstancesSearchQueryBody(array $criteria, array $locationIds, $includeInactive) {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->from($this->getEntityName(), 'ei')
            ->innerJoin('ei.equipment', 'e')
            ->where($qb->expr()->eq('e.application', ':application'))
            ->setParameter('application', $this->getApplication());

        /* Equipment taxonomies */
        if (isset($criteria['taxonomies'])) {
            foreach ($criteria['taxonomies'] as $key => $value) {
                if (!empty($value)) {
                    $qb->leftJoin('e.equipmentTaxonomy', $key)
                        ->andWhere($qb->expr()->eq($key . '.equipmentTaxonomyId', ':' . $key))
                        ->setParameter($key, $value);
                }
            }
        }

        /* Equipment */
        if (isset($criteria['equipment'])) {
            foreach ($criteria['equipment'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->eq('e.' . $key, ':' . $key))
                        ->setParameter($key, $value);
                }
            }
        }

        /* Equipment Instance attributes to use with like */
        if (isset($criteria['attributes_like'])) {
            foreach ($criteria['attributes_like'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->like('ei.' . $key, ':' . $key))->setParameter($key, '%' . $value . '%');
                }
            }
        }

        /* Equipment Instance attributes to use with equal */
        if (isset($criteria['attributes_equal'])) {
            foreach ($criteria['attributes_equal'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->eq('ei.' . $key, ':' . $key))->setParameter($key, $value);
                }
            }
        }

        if (!empty($locationIds)) {
            $ors = $qb->expr()->orX();
            foreach ($locationIds as $locationId) {
                $ors->add($qb->expr()->eq('ei.location', $locationId));
            }
            $qb->andWhere($ors);

        }

        if (array_key_exists('controlType', $criteria)) {
            $controlType = $criteria['controlType'];
        }
        $dateField = 'minPeriodicControlDate';
        if (!empty($controlType) && $controlType === 'visual') {
            $qb->andWhere("ei.visualControl = '1'");
            $dateField = 'minVisualControlDate';
        }
        if (!empty($criteria['fromDate'])) {
            $qb->andWhere('ei.' . $dateField . ' >= (:fromDate)')
                ->setParameter('fromDate', $criteria['fromDate']);
        }

        if (!empty($criteria['toDate'])) {
            $qb->andWhere('ei.' . $dateField . '  <= (:toDate)')
                ->setParameter('toDate', $criteria['toDate']);
        }

        if (!$includeInactive) {
            $qb->andWhere("ei.status = 'active'");
        }

        return $qb;
    }

    public function getNumberOfMinDatesFilled() {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->from($this->getEntityName(), 'ei')
            //->orWhere('ei.minPeriodicControlDate IS NOT NULL')
            //->orWhere('ei.minTechnicalLifetime IS NOT NULL')
            //->orWhere('ei.minGuaranteeTime IS NOT NULL')
            ->select('COUNT(ei.equipmentInstanceId)');
        $ors = $query->expr()->orX(
            'ei.minPeriodicControlDate IS NOT NULL',
            'ei.minTechnicalLifetime IS NOT NULL',
            'ei.minGuaranteeTime IS NOT NULL'
        );
        $query->andWhere($ors);
        $this->addApplicationFilterTo($query);
        return $query->getQuery()->getSingleScalarResult();
    }

    public function fetchPotentialChildren($equipmentInstanceId) {
        $dql = "SELECT ei
                FROM Equipment\Entity\EquipmentInstance ei
                WHERE (ei.parentId != (:parentId) OR ei.parentId IS NULL)
                AND ei.equipmentInstanceId != (:equipmentInstanceId)
                AND ei.application = (:application)
                AND ei.status = 'active'";

        $query = $this->getEntityManager()->createQuery($dql);
        $query
            ->setParameter('parentId', $equipmentInstanceId)
            ->setParameter('equipmentInstanceId', $equipmentInstanceId)
            ->setParameter('application', $this->getApplication());

        $result = $query->getResult();
        return $result;
    }


    public function deleteHistory($equipmentInstanceId) {
        $deleteHistoryDql = "DELETE Equipment\Entity\EquipmentInstanceHistorical history "
            . "WHERE history.equipmentInstance = (:equipmentInstanceId)";
        $query = $this->getEntityManager()->createQuery($deleteHistoryDql);
        $query->setParameter('equipmentInstanceId', $equipmentInstanceId);
        $query->execute();

    }

    private function getFieldFromType($expirationField) {
        switch($expirationField) {
            case InstanceExpirationFieldTypes::PERIODIC_CONTROL: return "minPeriodicControlDate";
            case InstanceExpirationFieldTypes::GUARANTEE: return "minGuaranteeTime";
            case InstanceExpirationFieldTypes::TECHNICAL_LIFETIME: return "minTechnicalLifetime";
            default: return null;
        }
    }

    /**
     * @param string $regNumber
     * @return bool
     */
    public function regNumberExists($regNumber, $excludeId, $equipmentId) {
        return $this->valueExists('regNumber', $regNumber, $excludeId, $equipmentId);
    }

    /**
     * @param string $regNumber
     * @return bool
     */
    public function serialNumberExists($serialNumber, $excludeId) {
        return $this->valueExists('serialNumber', $serialNumber, $excludeId);
    }

    /**
     * @param string $regNumber
     * @return bool
     */
    public function valueExists($fieldName, $value, $excludeId, $equipmentId = null) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('COUNT(ei)')
            ->from($this->getEntityName(), 'ei')
            ->innerJoin('ei.equipment', 'e')
            ->andWhere("ei.{$fieldName} = (:value)")
            ->setParameter('value', $value);

        if($equipmentId != null) {
            $queryBuilder->andWhere('e.equipmentId = :equipmentId')
                ->setParameter('equipmentId', $equipmentId);
        }

        if ($excludeId !== null) {
            $queryBuilder->andWhere('ei.equipmentInstanceId != (:excludeId)')
                ->setParameter('excludeId', $excludeId);
        }

        $result = (int)$queryBuilder->getQuery()->getSingleScalarResult();
        return $result > 0;
    }

    /**
     * This function get the maximum reg number that match a regular expression passed by parameter. For example: ^A[0-9]{5}$
     * For example A10000 is a valid value
     * @param string $like general like expression for improve the search performance
     * @param string $regexp regular expression to be evaluated
     * @return mixed
     */
    public function getLastRegNumber($like, $regexp) {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('reg_number', 'regNumber');
        $qb = $this->getEntityManager()->createNativeQuery(
            'SELECT MAX(ei.reg_number) AS reg_number
            FROM equipment_instance ei
            WHERE ei.reg_number LIKE ?
            AND ei.reg_number REGEXP ?', $rsm)
            ->setParameter(1, $like)
            ->setParameter(2, $regexp);
        return $qb->getSingleScalarResult();
    }

    public function getAllExpired($expirationField, $idType, $id) {
        $query = $this->getExpiredQueryBody($expirationField, $idType, $id);
        $query->select('ei');
        return $query->getQuery()->getResult();
    }

    public function getExpiredCount($expirationField, $idType, $id) {
        $query = $this->getExpiredQueryBody($expirationField, $idType, $id);
        $query->select('COUNT(ei.equipmentInstanceId)');
        return $query->getQuery()->getSingleScalarResult();
    }

    private function getExpiredQueryBody($expirationField, $idType, $id) {
        $field = $this->getFieldFromType($expirationField);
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->from($this->getEntityName(), 'ei')
            ->andWhere($queryBuilder->expr()->eq('ei.status', ":status"))
            ->setParameter('status', StatusConstants::ACTIVE)
            ->innerJoin('ei.equipment', 'e');

        if ($idType === 'category') {
            $queryBuilder->innerJoin('e.equipmentTaxonomy', 'c');

            $orQueries = $queryBuilder->expr()->orX();
            foreach ($id as $i => $categoryId) {
                $orQueries->add($queryBuilder->expr()->eq('c.equipmentTaxonomyId', $categoryId));
            }
            $queryBuilder->andWhere($orQueries);
        }
        else if ($idType === 'equipment') {
            $queryBuilder->andWhere("ei.equipment = ${id}");
        }

        $queryBuilder
            ->andWhere("ei.${field} IS NOT NULL")
            ->andWhere("ei.${field} < CURRENT_DATE()");
        $this->addApplicationFilterTo($queryBuilder);

        return $queryBuilder;
    }

    public function fetchAllByIds($equipmentInstanceIds) {
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('ei')
            ->from($this->getEntityName(), 'ei')
            ->andWhere($query->expr()->in('ei.equipmentInstanceId', $equipmentInstanceIds));
        return $query->getQuery()->getResult();
    }

    public function getEntitiesRelated($equipmentInstanceId) {
        $dqlForEquipmentInstanceChildren =
            "SELECT CONCAT('Equipment instance: ', eq.serialNumber,' (Serial number)') AS " . EquipmentInstanceService::ALIAS_KEY_RELATIONSHIPS . "
            FROM Equipment\Entity\EquipmentInstance eq
            WHERE eq.parentId = (:parentId)";
        $query = $this->getEntityManager()->createQuery($dqlForEquipmentInstanceChildren);
        $query->setParameter('parentId', $equipmentInstanceId);
        $equipmentInstanceChildren = $query->getResult();

        $dqlForAttachment =
            "SELECT CONCAT('It has ', COUNT(eia.attachmentId),' attachment(s)') AS " . EquipmentInstanceService::ALIAS_KEY_RELATIONSHIPS . "
            FROM Equipment\Entity\EquipmentInstanceAttachment eia
            WHERE eia.equipmentInstance = (:equipmentInstanceId)
            HAVING COUNT(eia.attachmentId) > 0";
        $query = $this->getEntityManager()->createQuery($dqlForAttachment);
        $query->setParameter('equipmentInstanceId', $equipmentInstanceId);
        $attachments = $query->getResult();

        $result = array_merge($equipmentInstanceChildren, $attachments);
        return $result;
    }

    private function addApplicationFilterTo(&$query) {
        $query->andWhere('ei.application = :application')
            ->setParameter('application', $this->getApplication());
    }

}
