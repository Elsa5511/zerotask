<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

class ApplicationRepository extends EntityRepository
{
    public function getTest($arrayConfig)
    {
        $dqlForEquipment =
        "SELECT CONCAT('Equipment instance: ', eq.serialNumber,' (Serial number)') AS abc 
            FROM Equipment\Entity\EquipmentInstance eq";
        $query = $this->getEntityManager()->createQuery($dqlForEquipment);
        $result = $query->getResult();        
        return $result;
    }
}
