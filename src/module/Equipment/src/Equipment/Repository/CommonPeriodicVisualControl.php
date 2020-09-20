<?php

namespace Equipment\Repository;

abstract class CommonPeriodicVisualControl extends \Acl\Repository\EntityRepository
{

    protected abstract function getEntityAlias();

    /**
     * @param array $criteria
     * @return int
     */
    public function getControlsSearchCount(array $criteria) {
        $entityAlias = $this->getEntityAlias();
        $qb = $this->getControlsSearchQueryBody($criteria);

        $qb->select("COUNT($entityAlias)");
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param array $criteria
     * @return array
     */
    public function getControlsSearch(array $criteria) {
        $entityAlias = $this->getEntityAlias();
        $qb = $this->getControlsSearchQueryBody($criteria);

        $qb->select($entityAlias);
        return $qb->getQuery()->getResult();
    }

    private function getControlsSearchQueryBody(array $criteria)
    {
        $entityAlias = $this->getEntityAlias();

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->from($this->getEntityName(), $entityAlias)
            ->innerJoin($entityAlias . '.equipmentInstance', 'ei')
            ->innerJoin('ei.equipment', 'e')
            ->orderBy($entityAlias . '.controlDate', 'DESC')
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

        /* Equipment equal comparator*/
        if (isset($criteria['equipment_equal'])) {
            foreach ($criteria['equipment_equal'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->eq('e.' . $key, ':' . $key))->setParameter($key , $value);
                }
            }
        }

        /* Equipment Instance */
        if (isset($criteria['equipment-instance'])) {
            foreach ($criteria['equipment-instance'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->eq('ei.' . $key, ':' . $key))
                        ->setParameter($key, $value);
                }
            }
        }

        /* Equipment Instance attributes to use with like */
        if (isset($criteria['equipment-instance_like'])) {
            foreach ($criteria['equipment-instance_like'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->like('ei.' . $key, ':' . $key))->setParameter($key, '%' . $value . '%');
                }
            }
        }

        /* Periodic Control attributes to use with equal */
        if (isset($criteria['attributes_equal'])) {
            foreach ($criteria['attributes_equal'] as $key => $value) {
                if (!empty($value)) {
                    $qb->andWhere($qb->expr()->eq($entityAlias . '.' . $key, ':' . $key))->setParameter($key, $value);
                }
            }
        }

        /* Periodic Control attributes to use with greater than equal or less than equal */
        if (isset($criteria['attributes_range'])) {
            foreach ($criteria['attributes_range'] as $key => $value) {
                if (!empty($value) && $key == 'fromDate') {
                    $dateValue = new \DateTime($value);
                    $qb->andWhere($qb->expr()->gte($entityAlias . '.controlDate', ':controlDateGTE'))->setParameter('controlDateGTE', $dateValue);
                } elseif (!empty($value) && $key == 'toDate') {
                    $dateValue = new \DateTime($value);
                    $qb->andWhere($qb->expr()->lte($entityAlias . '.controlDate', ':controlDateLTE'))->setParameter('controlDateLTE', $dateValue);
                }
            }
        }

        return $qb;
    }
}