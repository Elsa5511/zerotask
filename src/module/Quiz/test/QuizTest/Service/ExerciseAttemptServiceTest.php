<?php

namespace QuizTest\Service;

use QuizTest\BaseSetUp;
use Quiz\Entity\Exercise;
use Quiz\Entity\ExerciseAttempt;
use Quiz\Entity\QuizAttempt;
use Quiz\Service\ExerciseAttemptService;
use Quiz\Entity\Question;
use Quiz\Entity\Option;
use Quiz\Entity\ExerciseAttemptQuestionAndAnswers;

class ExerciseAttemptServiceTest extends BaseSetUp {

    public function testSearchWithoutPostData() {
        $postData = array();
        $expectUserList = null;
        $expectExerciseList = null;
        $expectedPostData = array();

        $repositoryMock = $this->getExerciseAttemptRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getExerciseBySearch')
                ->with($expectUserList, $expectExerciseList, $expectedPostData)
                ->will($this->returnValue(true));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        $exerciseAttemptService->search($postData);
    }

    public function testSearchWithUserList() {
        $userArray = array(1, 2, 3);
        $postData = array(
            'user' => $userArray
        );

        $expectUserList = $userArray;
        $expectExerciseList = null;
        $expectedPostData = array();

        $repositoryMock = $this->getExerciseAttemptRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getExerciseBySearch')
                ->with($expectUserList, $expectExerciseList, $expectedPostData)
                ->will($this->returnValue(true));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        $exerciseAttemptService->search($postData);
    }

    public function testSearchWithExerciseList() {
        $exerciseArray = array(1, 2, 3);
        $postData = array(
            'quiz' => $exerciseArray
        );

        $expectUserList = null;
        $expectExerciseList = $exerciseArray;
        $expectedPostData = array();

        $repositoryMock = $this->getExerciseAttemptRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getExerciseBySearch')
                ->with($expectUserList, $expectExerciseList, $expectedPostData)
                ->will($this->returnValue(true));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        $exerciseAttemptService->search($postData);
    }

    public function testSearchWithUserAndExerciseList() {
        $arrayWithIds = array(1, 2, 3);
        $postData = array(
            'quiz' => $arrayWithIds,
            'user' => $arrayWithIds,
        );

        $expectUserList = $arrayWithIds;
        $expectExerciseList = $arrayWithIds;
        $expectedPostData = array();

        $repositoryMock = $this->getExerciseAttemptRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getExerciseBySearch')
                ->with($expectUserList, $expectExerciseList, $expectedPostData)
                ->will($this->returnValue(true));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        $exerciseAttemptService->search($postData);
    }

    public function testSearchWithAllFieldsFilled() {
        $arrayWithIds = array(1, 2, 3);
        $postData = array(
            'quiz' => $arrayWithIds,
            'user' => $arrayWithIds,
            'another' => null
        );

        $expectUserList = $arrayWithIds;
        $expectExerciseList = $arrayWithIds;
        $expectedPostData = array('another' => null);

        $repositoryMock = $this->getExerciseAttemptRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getExerciseBySearch')
                ->with($expectUserList, $expectExerciseList, $expectedPostData)
                ->will($this->returnValue(true));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        $exerciseAttemptService->search($postData);
    }

    public function testFindById() {
        // Given
        $quizId = 1;
        $exerciseAttempt = new ExerciseAttempt();
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($quizId)
                ->will($this->returnValue($exerciseAttempt));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);

        // When
        $result = $exerciseAttemptService->findById($quizId);

        // Then
        $this->assertEquals($exerciseAttempt, $result);
    }

    /**
     * @group solo
     */
    public function testCreateNewAttempt() {
        // Given
        $question = new Question();
        $questions = array($question);
        $exerciseId = 1;
        $exercise = new \Quiz\Entity\Exercise();
        $exercise->setQuestions($questions);
        $userId = 1;
        $user = new \Application\Entity\User();
        $exerciseAttemptService = $this->getExerciseAttemptServiceForCreateNewAttemptTest(
                $exerciseId, $exercise, $userId, $user
        );

        // When
        $exerciseAttempt = $exerciseAttemptService->createNewAttempt($exerciseId, $userId);
        $questionsAndAnswers = $exerciseAttempt->getQuestionsAndAnswers();

        // Then
        $this->assertEquals($exerciseAttempt->getQuiz(), $exercise, 'Get exercise');
        $this->assertEquals($exerciseAttempt->getUser(), $user);
        $this->assertEquals(count($questionsAndAnswers), count($questions));
        $this->assertEquals($questionsAndAnswers[0]->getQuestion(), $questions[0]);
    }

    private function getExerciseAttemptServiceForCreateNewAttemptTest($exerciseId, $exercise, $userId, $user) {
        $entityManagerMock = $this->setupMocksForCreateNewAttemptTest(
                $exerciseId, $exercise, $userId, $user
        );
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);
        return $exerciseAttemptService;
    }

    private function setupMocksForCreateNewAttemptTest($exerciseId, $exercise, $userId, $user) {
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($exerciseId)
                ->will($this->returnValue($exercise));
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with(array('userId' => $userId))
                ->will($this->returnValue(array($user)));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist');

        return $entityManagerMock;
    }

    public function testFindAttemptInProgress() {
        // Given
        $exerciseId = 1;
        $filter = array(
            'quiz' => $exerciseId,
            'status' => QuizAttempt::STATUS_IN_PROGRESS
        );
        $exerciseAttempt = new ExerciseAttempt();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($filter)
                ->will($this->returnValue(array($exerciseAttempt)));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $exerciseAttemptService = $this->getExerciseAttemptService($entityManagerMock);

        // When 
        $exerciseAttemptResult = $exerciseAttemptService->findAttemptInProgress($exerciseId);

        // Then
        $this->assertEquals($exerciseAttempt, $exerciseAttemptResult);
    }

    public function testCreateStatusOverview() {
        // Given
        $questionsAndAnswers = $this->createQuestionAndAnswersArray();
        $exercise = new Exercise();
        $requiredScorePercentage = 90;
        $exercise->setRequiredForPass($requiredScorePercentage);
        $exerciseAttempt = new ExerciseAttempt();
        $exerciseAttempt->setQuiz($exercise);
        $exerciseAttempt->setQuestionsAndAnswers($questionsAndAnswers);
        $exerciseAttemptService = $this->getExerciseAttemptService();
        $exerciseAttemptService->createStatusOverview($exerciseAttempt);

        // When
        $statusOverview = $exerciseAttemptService->createStatusOverview($exerciseAttempt);

        // Then
        $this->assertEquals(3, $statusOverview->getQuestionsAnswered(), 'Questions answered');
        $this->assertEquals(4, $statusOverview->getTotalNoOfQuestions(), 'Total no. of questions');
        $this->assertEquals(14, $statusOverview->getTotalPossibleResult(), 'Total possible result');
        $this->assertEquals(ExerciseAttempt::STATUS_FAILED, $statusOverview->getCurrentStatus(), 'Current status');
        $this->assertEquals($requiredScorePercentage, $statusOverview->getRequiredScorePercentage(), 'Required score percentage');
        $this->assertEquals(50, (int) $statusOverview->getResultAsPercentage(), 'Result as percentage');
        $this->assertEquals(7, $statusOverview->getTotalResult(), 'Total result');
        $this->assertEquals(2, $statusOverview->getCorrectAnswers(), 'Correct answers');
    }

    private function createQuestionAndAnswersArray() {
        $questionsAndAnswers = array();
        array_push($questionsAndAnswers, $this->createQuestionAndAnswers(
                        Question::TYPE_SINGLE_CHOICE, 2, true, true));
        array_push($questionsAndAnswers, $this->createQuestionAndAnswers(
                        Question::TYPE_MULTI_CHOICE, 3, true, false));
        array_push($questionsAndAnswers, $this->createQuestionAndAnswers(
                        Question::TYPE_SINGLE_CHOICE, 4, false));
        array_push($questionsAndAnswers, $this->createQuestionAndAnswers(
                        Question::TYPE_MULTI_CHOICE, 5, true, true));
        return $questionsAndAnswers;
    }

    private function createQuestionAndAnswers($type, $weight, $hasAnswers, $answerCorrectly = false) {
        $questionAndAnswers = new ExerciseAttemptQuestionAndAnswers();
        $questionAndAnswers->setQuestion($this->createQuestion($type, $weight));
        if ($hasAnswers) {
            $selectedOptions = $this->answerQuestion(
                    $questionAndAnswers->getQuestion(), $answerCorrectly
            );
            $questionAndAnswers->setSelectedOptions($selectedOptions);
        }
        return $questionAndAnswers;
    }

    private function answerQuestion($question, $answerCorrectly) {
        $selectedOptions = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($question->getOptions() as $option) {
            if ($option->isCorrect() == $answerCorrectly) {
                $selectedOptions->add($option);
            }
        }
        return $selectedOptions;
    }

    private function createQuestion($type, $weight) {
        $question = new Question();
        $question->setWeight($weight);
        $question->setType($type);
        $question->addOption($this->createOption(false));
        $question->addOption($this->createOption(true));
        $question->addOption($this->createOption(false));
        $question->addOption($this->createOption(true));
        return $question;
    }

    public function testAnswersAreCorrect() {
        // Given 
        $singleChoiceCorrect = $this->createQuestionAndAnswers(Question::TYPE_SINGLE_CHOICE, 1, true, true);
        $multiChoiceCorrect = $this->createQuestionAndAnswers(Question::TYPE_MULTI_CHOICE, 1, true, true);
        $singleChoiceNoAnswer = $this->createQuestionAndAnswers(Question::TYPE_SINGLE_CHOICE, 1, false);
        $multiChoiceIncorrect = $this->createQuestionAndAnswers(Question::TYPE_MULTI_CHOICE, 1, true, false);
        $exerciseAttemptService = $this->getExerciseAttemptService();

        // Then
        $this->assertEquals(true, $exerciseAttemptService->answersAreCorrect($singleChoiceCorrect), 'Correct single choice');
        $this->assertEquals(true, $exerciseAttemptService->answersAreCorrect($multiChoiceCorrect), 'Correct multiple choice');
        $this->assertEquals(false, $exerciseAttemptService->answersAreCorrect($singleChoiceNoAnswer), 'Incorrect single choice');
        $this->assertEquals(false, $exerciseAttemptService->answersAreCorrect($multiChoiceIncorrect), 'Incorrect single choice, has answers, but all are not correct.');
    }

    public function testGetAnswerStatusWithCorrectAnswer() {
        $questionAndAnswers = $this->createQuestionAndAnswers(
                Question::TYPE_SINGLE_CHOICE, 2, true, true);
        $answerValidatorMock = $this->getMock('\Quiz\Service\QuizAnswerValidator');
        $answerValidatorMock->expects($this->once())
                ->method('answersAreCorrect')
                ->with($questionAndAnswers)
                ->will($this->returnValue(true));
        $exerciseAttemptService = $this->getExerciseAttemptService();
        $status = $exerciseAttemptService->getAnswerStatus($questionAndAnswers, $answerValidatorMock);
        $this->assertEquals('correct', $status);
    }

    public function testGetAnswerStatuses() {
        $questionAndAnswersArray = array();
        array_push($questionAndAnswersArray, $this->createQuestionAndAnswers(
                        Question::TYPE_SINGLE_CHOICE, 2, true, true));
        array_push($questionAndAnswersArray, $this->createQuestionAndAnswers(
                        Question::TYPE_MULTI_CHOICE, 1, true, false));
        $answerValidatorMock = $this->getMock('\Quiz\Service\QuizAnswerValidator');
        $answerValidatorMock->expects($this->exactly(count($questionAndAnswersArray)))
                ->method('answersAreCorrect')
                ->will($this->onConsecutiveCalls(true, false));
        $exerciseAttemptService = $this->getExerciseAttemptService();
        $statuses = $exerciseAttemptService->getAnswerStatuses($questionAndAnswersArray, $answerValidatorMock);
        $this->assertEquals(array('correct', 'incorrect'), $statuses);
    }

    public function testCreateReportTable() {
        // Given
        $user = new \Application\Entity\User();
        $user->setDisplayName('test-user');
        $exercise = new Exercise();
        $exercise->setName("test-exercise");
        $equipment = new \Equipment\Entity\Equipment();
        $equipment->setTitle('test-equipment');
        $exercise->setEquipment($equipment);
        $exerciseAttempts = $this->setupExerciseAttempts($user, $exercise);
        $exerciseAttemptService = $this->getExerciseAttemptService();

        // When
        $reportTable = $exerciseAttemptService->createReportTable($exerciseAttempts);

        // Then
        $this->assertEquals($reportTable->getHeaderColumns(), array(
            'User',
            'Exercise',
            'Equipment type',
            'Date',
            'Result',
            'Status'
        ));
        $this->assertEquals($reportTable->getNoOfColumns(), 6);
        $this->assertEquals($reportTable->getNoOfDataRows(), 3);
        $this->assertEquals($reportTable->getTitle(), 'Exercise Attempts report');
        
        $dataTable = $reportTable->getDataTable();
        for ($i = 0; $i < 3; $i++) {
            $dataRow = $dataTable[$i];
            $this->assertEquals($dataRow[0], $user->getDisplayName());
            $this->assertEquals($dataRow[1], $exercise);
            $this->assertEquals($dataRow[2], $equipment->getTitle());
            $this->assertTrue(is_a($dataRow[3], 'DateTime'));
            $expectedResultPercentage = ($i + 1) . '.0 %';
            $this->assertEquals($dataRow[4], $expectedResultPercentage);
            $this->assertEquals($dataRow[5], 'Not started');
        }
    }

    private function setupExerciseAttempts($user, $exercise) {
        $exerciseAttempts = array();

        for ($i = 0; $i < 3; $i++) {
            $exerciseAttempt = new ExerciseAttempt();
            $exerciseAttempt->setUser($user);
            $exerciseAttempt->setQuiz($exercise);
            $exerciseAttempt->setAttemptDate(new \DateTime());
            $exerciseAttempt->setResult($i + 1);
            $exerciseAttempt->setStatus('not-started');
            array_push($exerciseAttempts, $exerciseAttempt);
        }
        return $exerciseAttempts;
    }

    public function createReportTable($exerciseAttempts) {
        $headerValues = $this->createReportHeaderValues();
        $dataTable = array();
        foreach ($exerciseAttempts as $exerciseAttempt) {
            $dataRow = array(
                $exerciseAttempt->getUser()->getDisplayName(),
                $exerciseAttempt->getQuiz()->getName(),
                $exerciseAttempt->getQuiz()->getEquipment()->getTitle(),
                $exerciseAttempt->getAttemptDate(),
                $exerciseAttempt->getResult(),
                $this->translate($exerciseAttempt->getStatusForPrint())
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Exercise Attempts report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    private function createOption($isCorrect) {
        $incorrectOption = new Option();
        $incorrectOption->setIsCorrect($isCorrect);
        return $incorrectOption;
    }

    private function getExerciseAttemptRepositoryMock() {
        return $this->getMockBuilder('\Quiz\Repository\QuizAttemptRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getRepositoryMock() {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    public function getExerciseAttemptService($entityManagerMock = null) {
        $equipmentInstanceService = new ExerciseAttemptService(array(
            'entity_manager' => $entityManagerMock,
        ));
        return $equipmentInstanceService;
    }

    private function getEntityManagerMock($repositoryMock) {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}
