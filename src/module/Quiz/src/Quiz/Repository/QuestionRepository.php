<?php

namespace Quiz\Repository;

class QuestionRepository extends \Acl\Repository\EntityRepository
{

    /**
     * 
     * @param int $questionId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($questionId, $translator)
    {        
        $attempts = $this->getExerciseAttempts($questionId);
        if(count($attempts) > 0) {
            $resultKey = $translator->translate("Exercise attempts");
            $attempts = array($resultKey => $attempts);
        }

        return $attempts;
    }
    
    /**
     * 
     * @param int $questionId
     * @return array
     */
    private function getExerciseAttempts($questionId)
    {
        $attempts = $this->getExerciseAttemptRepository()->findBy(array('question' => $questionId));
        return $attempts;
    }
    
    /**
     * 
     * @param int $questionId
     * @return array
     */
    public function getExamAttempts($questionId)
    {
        $attempts = $this->getExamAttemptRepository()->findBy(array('question' => $questionId));
        return $attempts;
    }
    
    private function getExerciseAttemptRepository()
    {
        return $this->getEntityManager()
                ->getRepository('Quiz\Entity\ExerciseAttemptQuestionAndAnswers');
    }
    
    private function getExamAttemptRepository()
    {
        return $this->getEntityManager()
                ->getRepository('Quiz\Entity\ExamAttemptQuestionAndAnswers');
    }

}
