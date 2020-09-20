<?php

namespace Certification\Repository;


class CertificationRepository extends \Acl\Repository\EntityRepository
{

    public function getCertificationBeforeTime($days, $months)
    {
        $dql = "SELECT ct 
            FROM Certification\Entity\Certification ct
            WHERE CURRENT_DATE() = DATE_SUB(DATE_SUB(ct.expirationDate,:months ,'MONTH'),:days,'DAY' ) 
            AND ct.application = (:application)";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('days', $days);
        $query->setParameter('months', $months);
        $query->setParameter('application', $this->getApplication());
        return $certifications = $query->getResult();
    }
    
    public function getCertificationBySearch($equipmentList, $criteria)
    {        
        $dql = "SELECT ct 
            FROM Certification\Entity\Certification ct
            WHERE ct.equipment ";
        
        if($equipmentList) {
            $inClause = implode(",", $equipmentList);
            $dql .= "IN ($inClause)";
        } else {
            $dql .= "> 0";
        }
        
        foreach ($criteria as $field => $value) {
            $dql .= " AND ct.$field = $value";
        }
        
        $dql .= " AND ct.application = '".$this->getApplication()."'";
        
        $query = $this->getEntityManager()->createQuery($dql);
        
        return $certifications = $query->getResult();
    }
    
    public function getByPassedExpirationTime()
    {
        $dql = "SELECT ct 
            FROM Certification\Entity\Certification ct
            WHERE CURRENT_DATE() > ct.expirationDate 
            AND ct.application = (:application)";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('application', $this->getApplication());
        return $certifications = $query->getResult();
    }
    
}
