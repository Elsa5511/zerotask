<?php

namespace BestPractice\Repository;

use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository
{
    /**
     * 
     * @param string $identifier
     */
    public function deleteSubscribersByIdentifier($identifier) {
        $dql = "DELETE FROM
            BestPractice\Entity\Subscription s
            where s.identifier = :identifier";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->execute(array('identifier' => $identifier));
    }
    
    /**
     * 
     * @param string $identifier
     * @param boolean $pendingNotificacion
     */
    public function updatePendingNotificacionByIdentifier($identifier, $pendingNotificationId){
        $dql = "UPDATE
            BestPractice\Entity\Subscription s
            SET s.pendingNotificationId = :pendingNotificationId
            WHERE s.identifier = :identifier";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->execute(array(
            'pendingNotificationId' => $pendingNotificationId,
            'identifier' => $identifier));
    }
    
    /**
     * 
     * @return \BestPractice\Entity\Subscription[] | null
     */
    public function getSubscriptionsToNotify(){
        $dql = "SELECT s
            FROM BestPractice\Entity\Subscription s
            WHERE s.pendingNotificationId <> 0";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
    
}