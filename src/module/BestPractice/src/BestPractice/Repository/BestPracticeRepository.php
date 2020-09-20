<?php

namespace BestPractice\Repository;

class BestPracticeRepository extends \Acl\Repository\EntityRepository
{    
    /**
     * 
     * @param integer $equipmentId
     * @return array
     */
    public function getLastIdentifiersDatesByEquipment($equipmentId) {
        $dql = "SELECT 
            bp.identifier,
            MAX(bp.revisionDate) AS revisionDate
            FROM BestPractice\Entity\BestPractice bp
            where bp.equipment = :equipmentId
            GROUP BY bp.identifier";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('equipmentId', $equipmentId);
        return $query->getResult();
    }
    
    /**
     * 
     * @param integer $equipmentId
     * @return ArrayOfBestPractice
     */
    public function getLastRevisionsByEquipment($equipmentId) {
        $lastReviews = $this->getLastIdentifiersDatesByEquipment($equipmentId);

        $dql = "SELECT bp
            FROM 
            BestPractice\Entity\BestPractice bp
            WHERE bp.equipment = :equipmentId 
            ";

        $formattedLastReviews = $this->getFormattedIdentifiersDates($lastReviews);
        if (count($formattedLastReviews) > 0) {
            $inClause = implode(",", $formattedLastReviews);
            $dql = $dql . " AND CONCAT(bp.identifier, bp.revisionDate) IN ($inClause) ";
        }

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('equipmentId', $equipmentId);
        return $query->getResult();
    }
    
    /**
     * 
     * @param array $lastReviews
     * @return array
     */
    private function getFormattedIdentifiersDates($lastReviews) {
        $arrayLastReviews = array();
        if (is_array($lastReviews) && count($lastReviews) > 0) {
            foreach ($lastReviews as $lastReview) {
                $whereIn = "'" . $lastReview['identifier'] . $lastReview['revisionDate'] . "'";
                array_push($arrayLastReviews, $whereIn);
            }
        }

        return $arrayLastReviews;
    }

}
