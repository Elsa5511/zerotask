<?php

namespace Quiz\Service;

use Sysco\Aurora\Stdlib\DateTime;
use Quiz\Entity\QuizAttempt;

class ExerciseAttemptService extends QuizAttemptService {

    public function search($postData) {
        if (array_key_exists('user', $postData)) {
            $userList = $postData['user'];
            unset($postData['user']);
        } else {
            $userList = null;
        }

        if (array_key_exists('quiz', $postData)) {
            $exerciseList = $postData['quiz'];
            unset($postData['quiz']);
        } else {
            $exerciseList = null;
        }

        $attempts = $this->getEntityRepository()
                ->getExerciseBySearch($userList, $exerciseList, $postData);
        return $attempts;
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

    private function createReportHeaderValues() {
        return array(
            $this->translate('User'),
            $this->translate('Exercise'),
            $this->translate('Equipment type'),
            $this->translate('Date'),
            $this->translate('Result'),
            $this->translate('Status'));
    }

    public function createNewAttempt($exerciseId, $userId) {
        $exercise = $this->getExerciseRepository()->find($exerciseId);
        $exerciseAttempt = $this->createAndInitializeExerciseAttempt($exercise, $userId);
        $questions = $exercise->getQuestions();

        foreach ($questions as $question) {
            $questionAndAnswer = $this->createAndInitializeQuestionAndAnswer($exerciseAttempt, $question);
            $exerciseAttempt->getQuestionsAndAnswers()->add($questionAndAnswer);
        }

        $this->persist($exerciseAttempt);

        return $exerciseAttempt;
    }

    public function findAttemptInProgress($exerciseId) {
        $filter = array(
            'quiz' => $exerciseId,
            'status' => QuizAttempt::STATUS_IN_PROGRESS
        );
        $exerciseAttempts = $this->getEntityRepository()->findBy($filter);
        if (count($exerciseAttempts) > 0) {
            return $exerciseAttempts[0];
        } else {
            return null;
        }
    }

    private function createAndInitializeExerciseAttempt($exercise, $userId) {
        $exerciseAttempt = new \Quiz\Entity\ExerciseAttempt();
        $exerciseAttempt->setAttemptDate(new DateTime());
        $exerciseAttempt->setQuiz($exercise);
        $exerciseAttempt->setUser($this->getUser($userId));
        $exerciseAttempt->setStatus(QuizAttempt::STATUS_IN_PROGRESS);
        return $exerciseAttempt;
    }

    private function createAndInitializeQuestionAndAnswer($exerciseAttempt, $question) {
        $questionAndAnswer = new \Quiz\Entity\ExerciseAttemptQuestionAndAnswers();
        $questionAndAnswer->setQuizAttempt($exerciseAttempt);
        $questionAndAnswer->setQuestion($question);
        return $questionAndAnswer;
    }

    public function persistExerciseAttempt($exerciseAttempt) {
        parent::persist($exerciseAttempt);
        return $exerciseAttempt->getExerciseAttemptId();
    }

    protected function getEntityRepository() {
        return $this->getRepository('Quiz\Entity\ExerciseAttempt');
    }

    private function getExerciseRepository() {
        return $this->getRepository('Quiz\Entity\Exercise');
    }

    private function getUser($userId) {
        $userRepository = $this->getEntityManager()->getRepository('Application\Entity\User');
        $users = $userRepository->findBy(array('userId' => $userId));
        return $users[0];
    }

}
