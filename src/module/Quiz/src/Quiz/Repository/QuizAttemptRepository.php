<?php

namespace Quiz\Repository;

class QuizAttemptRepository extends \Acl\Repository\EntityRepository
{
    
    public function findUserAttempts($userId, $examIds) 
    {
        $dql = "SELECT et
                FROM Quiz\Entity\ExamAttempt et
                WHERE et.user = :userId";

        $dql .= " AND et.quiz ";
        $dql .= $this->getDqlLineForEntityList($examIds);

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter("userId", $userId);        
        
        return $query->getResult();     
    }
    
    public function getExamBySearch($userList, $examList, $criteria)
    {        
        $dql = "SELECT et
                FROM Quiz\Entity\ExamAttempt et
                WHERE et.user ";
        $dql .= $this->getDqlLineForEntityList($userList);

        $dql .= " AND et.quiz ";
        $dql .= $this->getDqlLineForEntityList($examList);
        
        foreach ($criteria as $field => $value) {
            $dql .= " AND et.$field = '$value'";
        }
        
        $dql .= " AND et.application = '".$this->getApplication()."'";

        $query = $this->getEntityManager()->createQuery($dql);
        
        return $query->getResult();
    }
    
    public function getExerciseBySearch($userList, $exerciseList, $criteria)
    {        
        $dql = "SELECT et
                FROM Quiz\Entity\ExerciseAttempt et
                WHERE et.user ";
        $dql .= $this->getDqlLineForEntityList($userList);
        
        $dql .= " AND et.quiz ";
        $dql .= $this->getDqlLineForEntityList($exerciseList);
        
        foreach ($criteria as $field => $value) {
            $dql .= " AND et.$field = '$value'";
        }
        
        $dql .= " AND et.application = '".$this->getApplication()."'";

        $query = $this->getEntityManager()->createQuery($dql);
        
        return $query->getResult();
    }
    
    /**
     * 
     * 
     * @param array|null $entityList
     * @return string
     */
    private function getDqlLineForEntityList($entityList)
    {
        if ($entityList) {
            $inClause = implode(",", $entityList);
            $dqlLine = "IN ($inClause)";
        } else {
            $dqlLine = "> 0";
        }
        return $dqlLine;
    }

}
