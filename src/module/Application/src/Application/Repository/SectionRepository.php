<?php

namespace Application\Repository;

use Acl\Repository\EntityRepository;

abstract class SectionRepository extends EntityRepository
{
    public function getPossibleParents(\Application\Entity\Section $section, $ownerFieldname, $owner)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        
        $queryBuilder->select('section')
                ->from($this->getEntityName(), 'section')
                ->andWhere($queryBuilder->expr()->eq('section.'.$ownerFieldname, '?1'))
                ->andWhere($queryBuilder->expr()->isNull('section.parent'))
                ->setParameter(1, $owner);
        
        if($section->getSectionId() !== null) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq('section.sectionId', '?2'))
                ->setParameter(2, $section->getSectionId());
        }
        
        return $queryBuilder->getQuery()->getResult();
    }

    public function getInlineSectionsByArray($sectionIdAttributeName, array $values)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        return $queryBuilder->select('s')
            ->from($this->getEntityName(), 's')
            ->andWhere($queryBuilder->expr()->in("s.$sectionIdAttributeName", '?1'))
            ->setParameter(1, $values)
            ->getQuery()
            ->getResult();
    }

    public function searchByWords($words, $ownerFieldname, $owner)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('s')
            ->from($this->getEntityName(), 's')
            ->andWhere($queryBuilder->expr()->like('s.label', '?1'))
            ->andWhere($queryBuilder->expr()->in('s.'.$ownerFieldname, '?2'))
            ->setParameter(1, "%$words%")
            ->setParameter(2, $owner)
            ->orderBy("s.$ownerFieldname");

        return $queryBuilder->getQuery()->getResult();
    }
    
    abstract public function hasContent(\Application\Entity\Section $section);
    
}
