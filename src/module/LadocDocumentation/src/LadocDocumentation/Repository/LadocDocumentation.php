<?php

namespace LadocDocumentation\Repository;

use Acl\Repository\EntityRepository;

class LadocDocumentation extends EntityRepository {
    public function customFindBy($criteria = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('ld')
            ->from($this->getEntityName(), 'ld')
            ->join("ld.equipment", "e")
            ->andWhere(sprintf("ld.application = '%s'", $this->getApplication()))
            ->andWhere("e.status = 'active'");
        foreach($criteria as $k => $v) {
            if($k != "template_type")
                $qb->andWhere($qb->expr()->eq('ld.' . $k, ':' . $k))->setParameter($k, $v);
        }

        $result = $qb->getQuery()->getResult();

        if (isset($criteria['template_type']) && $criteria['template_type']) {
            $filteredResult = array();
            foreach ($result as $r) {
                if($criteria['template_type'] == $r->getLowestTaxonomyTemplateType())
                    $filteredResult[] = $r;
            }

            return $filteredResult;
        }

        return $result;
    }
}