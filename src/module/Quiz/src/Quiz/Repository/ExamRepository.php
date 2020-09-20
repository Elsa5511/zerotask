<?php

namespace Quiz\Repository;

class ExamRepository extends \Acl\Repository\EntityRepository
{
    
    /**
     * 
     * @param int $examId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($examId, $translator)
    {        
        $examAttempts = $this->getExamAttempts($examId);
        if(count($examAttempts) > 0) {
            $resultKey = $translator->translate("Exam attempts");
            $examAttempts = array($resultKey => $examAttempts);
        }

        $result = $examAttempts;
        return $result;
    }
    
    /**
     * 
     * @param int $examId
     * @return array
     */
    private function getExamAttempts($examId)
    {
        $repository = $this->getEntityManager()
                            ->getRepository('Quiz\Entity\ExamAttempt');
        $exams = $repository->findBy(array('quiz' => $examId));
        return $exams;
    }

}
