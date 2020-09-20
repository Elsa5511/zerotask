<?php

namespace Quiz\Service;

use Application\Service\AbstractBaseService;

class QuizService extends AbstractBaseService
{

    protected function getEntityRepository()
    {
        return $this->getRepository($this->quizRepositoryString);
    }

    public function getQuiz($quizId)
    {
        return $this->getEntityRepository()->find($quizId);
    }

    public function getQuizzes($filter = array())
    {
        $quizzes = $this->getEntityRepository()->findBy($filter);

        return $quizzes;
    }
    
    public function getExercisesWithAttemptsAggregate($userId, $equipmentId) {
        return $this->getEntityRepository()->getExercisesWithAttemptsAggregate($userId, $equipmentId);
    }

    public function persistData($quiz)
    {
        parent::persist($quiz);
        return $quiz->getQuizId();
    }
    
    public function persistExamData(\Quiz\Entity\Exam $exam, $questionsArray)
    {
        $exam->removeQuestions();
        if($questionsArray) {
            foreach ($questionsArray as $questionId) {
                $question = $this->getQuestionRepository()->find($questionId);
                if($question) {
                    $exam->addQuestion($question);
                }
            }
        }
        
        parent::persist($exam);
        return $exam->getQuizId();
    }

    public function getNewQuiz($equipmentId)
    {
        $equipment = $this->getEquipmentRepository()->find($equipmentId);

        if (!$equipment) {
            throw new \Application\Service\EntityDoesNotExistException($this->translate('Equipment does not exist.'));
        }

        $quiz = $this->childEntity;
        $quiz->setEquipment($equipment);
        
        return $quiz;
    }

    protected function getEquipmentRepository()
    {
        return $this->getRepository('Equipment\Entity\Equipment');
    }
    
    /**
     * 
     * @param type $quizId
     * @throws EntityRelatedException
     * @throws EntityDoesNotExistException
     * @throws \Exception
     */
    public function deleteById($quizId)
    {        
        $quiz = $this->getQuiz($quizId);
        if ($quiz) {
            $entitiesRelated = $this->getEntitiesRelated($quizId);
            $isRelated = count($entitiesRelated) > 0;

            if ($isRelated) {
                $message = $this->getRelationshipErrorMessage($entitiesRelated);
                $errorMessage = sprintf($message, $quiz->getName());
                $this->displayEntityRelatedException($errorMessage);

            } else {
                return parent::remove($quiz);
            }

        } else {
            $this->displayEntityNotExistException(
                    $this->translate('Quiz doesn\'t exist'));
        }

        throw new \Exception($this->translate('Could not delete quiz.'));
    }

    protected function getQuestionRepository()
    {
        return $this->getEntityManager()->getRepository('Quiz\Entity\Question');
    }

}