<?php

namespace QuizTest\Service;

use QuizTest\BaseSetUp;
use Quiz\Entity\Exercise;
use Quiz\Service\QuizService;
use Equipment\Entity\Equipment;

class  QuizServiceTest extends BaseSetUp
{

    public function testGetNewQuiz()
    {
        // input
        $quizId = $equipmentId = 1;
        $equipment = new Equipment();
        $equipment->setEquipmentId($equipmentId);
        
        $quiz = new Exercise();
        $quiz->setEquipment($equipment);
        // arrangement / asserts
        $repositoryMock = $this->getQuizRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($quizId))
                ->will($this->returnValue($equipment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $quizService = $this->getQuizService($entityManagerMock,$repositoryMock,$quiz);

        // execute
        $result = $quizService->getNewQuiz($equipmentId);
        $this->assertEquals($result,$quiz);
    }

    public function testGetQuizById()
    {
        // input
        $quizId = 1;
        $quiz = new Exercise();

        // arrangement / asserts
        $repositoryMock = $this->getQuizRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($quizId))
                ->will($this->returnValue($quiz));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $quizService = $this->getQuizService($entityManagerMock,$repositoryMock,$quiz);

        // execute
        $quizService->getQuiz($quizId);
    }

    public function testGetQuizzes()
    {
        // input
        $expectedResult = array();

        // arrangement / asserts
        $repositoryMock = $this->getQuizRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array()))
                ->will($this->returnValue($expectedResult));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseService = $this->getQuizService($entityManagerMock,$repositoryMock);

        // execute
        $result = $exerciseService->getQuizzes();
        $this->assertEquals($result,$expectedResult );
    }

    public function testPersistData()
    {
        // input
        $quizId = 1;
        $quiz = new Exercise();
        $quiz->setQuizId($quizId);

        // arrangement
        $repositoryMock = $this->getQuizRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($quiz))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $quizService = $this->getQuizService($entityManagerMock,$repositoryMock,$quiz);

        // asserts
        $expectedId = $quizService->persistData($quiz);
        $this->assertEquals($quizId, $expectedId);
    }

    public function testDeleteById()
    {
        $quizId = 1;
        $quiz = new Exercise();
        $quiz->setQuizId($quizId);

        $repositoryMock = $this->getQuizRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($quizId))
                ->will($this->returnValue($quiz));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($quiz))
                ->will($this->returnValue($quiz));

        $quizService =  $this->getQuizService($entityManagerMock,$repositoryMock,$quiz);

        // asserts
        $expectedResult = $quizService->deleteById($quizId);
        $this->assertEquals($quiz, $expectedResult);
    }

    public function getQuizService($entityManagerMock,$repositoryMock,$newEntity = null)
    {
        $quizService = new QuizService(array(
            'entity_manager' => $entityManagerMock,
            'quiz_repository' => $repositoryMock,
            'quiz_repository_string' => null,
            'child_entity' => $newEntity,
            'dependencies' => array(
                'translator' => null
            )
        ));
        return $quizService;
    }

    private function getQuizRepositoryMock()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
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