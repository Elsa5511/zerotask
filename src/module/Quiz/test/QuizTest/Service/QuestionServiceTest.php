<?php

namespace QuizTest\Service;

use QuizTest\BaseSetUp;
use Quiz\Entity\Exercise;
use Quiz\Entity\Question;
use Quiz\Service\QuestionService;

class  QuestionServiceTest extends BaseSetUp
{

    public function testGetNewQuestion()
    {
        // input
        $quizId = 1;
        $exercise = new Exercise();
        $exercise->setQuizId($quizId);
        
        $question = new Question();
        $question->setExercise($exercise);

        // arrangement / asserts
        $repositoryMock = $this->getExerciseRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($quizId))
                ->will($this->returnValue($exercise));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $questionService = $this->getQuestionService($entityManagerMock, $repositoryMock);

        // execute
        $result = $questionService->getNewQuestion($quizId);
        $this->assertEquals($result, $question);
    }

    public function testGetQuestionsByExercise()
    {
        // input
        $exerciseId = 1;
        $expectedResult = array();

        // arrangement / asserts
        $repositoryMock = $this->getExerciseRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array('exercise' => $exerciseId)))
                ->will($this->returnValue($expectedResult));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $questionService = $this->getQuestionService($entityManagerMock, $repositoryMock);

        // execute
        $result = $questionService->getQuestionsByExercise($exerciseId);
        $this->assertEquals($result, $expectedResult);
    }

    public function testPersistData()
    {
        // input
        $questionId = 1;
        $question = new Question();
        $question->setQuestionId($questionId);
        $imageInput = array(
          'tmp_name' => null  
        );

        // arrangement
        $repositoryMock = $this->getQuestionRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($question))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $questionService = $this->getQuestionService($entityManagerMock, $repositoryMock);

        // asserts
        $expectedId = $questionService->persistData($question, $imageInput, 0);
        $this->assertEquals($questionId, $expectedId);
    }
    
    public function testUpdateOptions()
    {
        // input
        $question = new Question();
        $optionsInput = array();

        // arrangement
        $repositoryMock = $this->getQuestionRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);        
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $questionService = $this->getQuestionService($entityManagerMock, $repositoryMock);

        // asserts
        $questionService->updateOptions($optionsInput, $question, false);
    }
    
    /**
     * @group ruru
     */
    public function testDeleteById()
    {
        // input
        $quizId = 1;
        $exercise = new Exercise();
        $exercise->setQuizId($quizId);

        $questionId = 1;
        $question = new Question();
        $question->setQuestionId($questionId);
        $question->setExercise($exercise);
        $question->setOrderNumber(-1);
        $entitiesRelated = array();

        $repositoryMock = $this->getQuestionRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($questionId))
                ->will($this->returnValue($question));
        $repositoryMock->expects($this->once())
                ->method('getEntitiesRelated')
                ->with($this->equalTo($questionId))
                ->will($this->returnValue($entitiesRelated));
        
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($question))
                ->will($this->returnValue($quizId));

        $questionService =  $this->getQuestionService($entityManagerMock, $repositoryMock);

        // asserts
        $expectedResult = $questionService->deleteById($questionId);
        $this->assertEquals($quizId, $expectedResult);
    }

    private function getQuestionService($entityManagerMock,$repositoryMock)
    {
        $questionService = new QuestionService(array(
            'entity_manager' => $entityManagerMock,            
            'question_repository' => $repositoryMock,
            'image' => null,
            'dependencies' => array(
                'translator' => null
            )
        ));
        return $questionService;
    }

    private function getQuestionRepositoryMock()
    {
        return $this->getMockBuilder('\Quiz\Repository\QuestionRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    private function getExerciseRepositoryMock()
    {
        return $this->getMockBuilder('\Quiz\Repository\ExerciseRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getEntityManagerMock($repositoryMock)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}