<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function getUsersByRole($roleId, $order = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('u')
                ->from($this->getEntityName(), 'u')
                ->andWhere(':role MEMBER OF u.roles')
                ->setParameter('role', $roleId);

        if ($order) {
            $qb->orderBy($order);
        }

        return $qb->getQuery()->getResult();
    }

}
