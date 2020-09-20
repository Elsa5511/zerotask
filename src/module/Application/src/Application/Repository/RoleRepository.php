<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{

     /**
     * Return an array with options to be used in a Form
     *
     */
    public function getAllowableParents($criteria = array())
    {
        $roleCriteria = array('guest');

        if (array_key_exists('roleId', $criteria)) {
            $roleCriteria['roleId'] = $criteria['roleId'];
        }

        $qb = $this->_em->createQueryBuilder();

        $qb->select('r')
                ->from($this->_entityName, 'r')
                ->andWhere($qb->expr()->notIn('r.roleId', ':role'))
                ->setParameter('role', $roleCriteria)
                ->orderBy('r.roleId', 'DESC');

        return $qb->getQuery()->getResult();
    }
   
    public function getAllButGuest()
    {        
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('r')
                ->from($this->_entityName, 'r')
                ->andWhere($queryBuilder->expr()->neq('r.roleId', ':role'))
                ->setParameter('role', 'guest')
                ->orderBy('r.roleId', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
    
    /**
     * 
     * @param String $roleId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($roleId, $translator)
    {
        $result = array();        
        $tableAlias = 'r';
        
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $criteria = $queryBuilder->expr()->eq($tableAlias . ".parent", ":role");

        $queryBuilder->select($tableAlias)
                ->from($this->getEntityName(), $tableAlias)
                ->andWhere($criteria)
                ->setParameter("role", $roleId)
                ->orderBy($tableAlias . ".roleId", "ASC");

        $roles = $queryBuilder->getQuery()->getResult();
        if(count($roles) > 0) {
            $resultKey = $translator->translate("Role");
            $result = array($resultKey => $roles);
        }
        
        return $result;
    }
}