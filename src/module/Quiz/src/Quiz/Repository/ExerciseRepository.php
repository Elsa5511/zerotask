<?php

namespace Quiz\Repository;

use Quiz\Entity\QuizWithAttemptsAggregate;
use Quiz\Entity\QuizAttempt;

class ExerciseRepository extends \Acl\Repository\EntityRepository {

    public function getExercisesWithAttemptsAggregate($userId, $equipmentId) {
        $statement = $this->createConnectionStatementForExerciseWithAggregatedQuizAttempts($userId, $equipmentId);
        $statement->execute();
        $exercisesWithAttemptsAggregate = array();
        
        while ($row = $statement->fetch()) {
            array_push($exercisesWithAttemptsAggregate, $this->createExerciseWithAttemptsAggregate($row));
        }
        
        return $exercisesWithAttemptsAggregate;
    }

    private function createExerciseWithAttemptsAggregate($row) {
        $exerciseStatus = $this->calculateExerciseStatus($row['attemptStatuses']);
        return new QuizWithAttemptsAggregate(
                $row['quizId'], $row['name'], $row['lastAttempt'],
                $row['bestResult'], $exerciseStatus
        );
    }

    private function createConnectionStatementForExerciseWithAggregatedQuizAttempts($userId, $equipmentId) {
        $sql = 'SELECT exercise.quiz_id AS quizId, exercise.equipment_id, exercise.name, attempts.* FROM equipment_exercise exercise '
                . 'LEFT OUTER JOIN (SELECT exercise_id, user_id, MAX(attempt_date) AS lastAttempt, MAX(result) AS bestResult, GROUP_CONCAT(status) AS attemptStatuses '
                . 'FROM equipment_exercise_attempt '
                . 'WHERE user_id = ? '
                . 'GROUP BY exercise_id) attempts ON exercise.quiz_id = attempts.exercise_id '
                . 'WHERE exercise.equipment_id = ? ';

        $connection = $this->getEntityManager()->getConnection();
        $preparedStatement = $connection->prepare($sql);
        $preparedStatement->bindValue(1, $userId);
        $preparedStatement->bindValue(2, $equipmentId);
        return $preparedStatement;
    }
    
    private function calculateExerciseStatus($exerciseAttemptStatuses) {
        
        if (strpos($exerciseAttemptStatuses, QuizAttempt::STATUS_IN_PROGRESS) !== false) {
            return QuizAttempt::STATUS_IN_PROGRESS;
        }
        else if (strpos($exerciseAttemptStatuses, QuizAttempt::STATUS_PASSED) !== false) {
            return QuizAttempt::STATUS_PASSED;
        }
        else if (strpos($exerciseAttemptStatuses, QuizAttempt::STATUS_FAILED) !== false) {
            return QuizAttempt::STATUS_FAILED;
        }
        else {
            return QuizAttempt::STATUS_NOT_STARTED;
        }
    }
    
    /**
     * 
     * @param int $exerciseId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($exerciseId, $translator)
    {        
        $exams = $this->getExams($exerciseId);
        if(count($exams) > 0) {
            $resultKey = $translator->translate("Exams");
            $exams = array($resultKey => $exams);
        }

        $result = $exams;
        return $result;
    }
    
    /**
     * 
     * @param int $exerciseId
     * @return array
     */
    private function getExams($exerciseId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Quiz\Entity\Exam');
        $exams = $repository->findBy(array('baseOnPracticeExercise' => $exerciseId));
        return $exams;
    }

}
