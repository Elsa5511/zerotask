<?php

namespace Quiz\Service;

use Quiz\Entity\ExamAttempt;

class ExamAttemptService extends QuizAttemptService {

    public function search($postData) {
        if (array_key_exists('user', $postData)) {
            $userList = $postData['user'];
            unset($postData['user']);
        } else {
            $userList = null;
        }

        if (array_key_exists('quiz', $postData)) {
            $examList = $postData['quiz'];
            unset($postData['quiz']);
        } else {
            $examList = null;
        }

        $attempts = $this->getEntityRepository()
                ->getExamBySearch($userList, $examList, $postData);
        return $attempts;
    }

//    public function getExamAttempt($examAttemptId) {
//        $examAttempt = $this->getEntityRepository()->find($examAttemptId);
//        if ($examAttempt === null) {
//            throw new EntityDoesNotExistException($this->translate("Exam attempt does not exist."));
//        }
//        else {
//            return $examAttempt;
//        }
//    }
//    
//    
    /**
     * Delete an exam attempt by Id
     * 
     * @param integer $attemptId
     * @return true if attempt was deleted
     * @throws \Exception
     */
    public function deleteById($attemptId) {
        $examAttempt = $this->findById($attemptId);

        if ($examAttempt) {
            parent::remove($examAttempt);
            return true;
        }

        throw new \Exception($this->translate('Could not delete exam attempt.'));
    }

    /**
     * 
     * @return \Quiz\Entity\ExamAttempt
     */
    public function getNewExamAttempt() {
        $examAttempt = new ExamAttempt();
        return $examAttempt;
    }

    public function startAttempt(ExamAttempt $examAttempt) {
        $examAttempt->setStatus(ExamAttempt::STATUS_IN_PROGRESS);
        $examAttempt->setAttemptDate(new \DateTime());
        $examAttempt->setStartTime(new \DateTime());
        $this->persist($examAttempt);
    }

    public function createExamAttempt(ExamAttempt $examAttempt) {
        $exam = $examAttempt->getQuiz();
        $numberOfQuestions = $exam->getNumberOfQuestions();

        if ($numberOfQuestions > 0) {
            $questionsToInclude = $this->createExamAttemptQuestions($exam, $numberOfQuestions);
            foreach ($questionsToInclude as $question) {
                $questionAndAnswer = $this->getNewQuestionAndAnswer($examAttempt, $question);
                $examAttempt->getQuestionsAndAnswers()->add($questionAndAnswer);
            }

            $this->persist($examAttempt);
            return $examAttempt->getAttemptId();
        } else {
            return 0;
        }
    }

    private function createExamAttemptQuestions($exam, $numberOfQuestions) {
        $examObligatoryQuestions = $exam->getQuestions()->getValues();
        $obligatoryNumber = count($examObligatoryQuestions);

        if ($numberOfQuestions < $obligatoryNumber) {
            $finalQuestions = array_slice($examObligatoryQuestions, 0, $numberOfQuestions);
        } else {
            $exercise = $exam->getBaseOnPracticeExercise();
            $exerciseQuestions = $exercise->getQuestions()->getValues();
            $obligatoryQuestionsIds = $exam->getQuestionsIds();
            $finalQuestions = $this->getFinalQuestions($exerciseQuestions, $examObligatoryQuestions, $numberOfQuestions, $obligatoryQuestionsIds);
        }

        return $finalQuestions;
    }

    private function getFinalQuestions($exerciseQuestions, $examObligatoryQuestions, $numberOfQuestions, $obligatoryQuestionsIds) {
        $numberOfExerciseQuestions = count($exerciseQuestions);
        if ($numberOfQuestions >= $numberOfExerciseQuestions) {
            $finalQuestions = $exerciseQuestions;
        } else {
            $finalQuestions = $examObligatoryQuestions;
            $remainingNoOfQuestions = ($numberOfQuestions - count($examObligatoryQuestions));
            if ($remainingNoOfQuestions > 0) {
                $nonObligatoryQuestions = $this->getNonObligatoryQuestions($exerciseQuestions, $obligatoryQuestionsIds);
                $randomQuestions = $this->getRandomQuestions($nonObligatoryQuestions, $remainingNoOfQuestions);
                $finalQuestions = array_merge($examObligatoryQuestions, $randomQuestions);
            }
        }
        return $finalQuestions;
    }

    private function getNonObligatoryQuestions($exerciseQuestions, $obligatoryQuestionsIds) {
        $nonObligatoryQuestions = array();

        foreach ($exerciseQuestions as $question) {
            $questionId = $question->getQuestionId();
            $isObligatory = in_array($questionId, $obligatoryQuestionsIds);
            if (!$isObligatory) {
                $nonObligatoryQuestions[] = $question;
            }
        }
        return $nonObligatoryQuestions;
    }

    private function getRandomQuestions($nonObligatoryQuestions, $remainingNoOfQuestions) {
        $randomQuestions = array();
        $chosenQuestionIndexes = array_rand($nonObligatoryQuestions, $remainingNoOfQuestions);

        if (is_array($chosenQuestionIndexes)) {
            foreach ($chosenQuestionIndexes as $chosenQuestionIndex) {
                $randomQuestions[] = $nonObligatoryQuestions[$chosenQuestionIndex];
            }
        } else {
            $randomQuestions[] = $nonObligatoryQuestions[$chosenQuestionIndexes];
        }

        return $randomQuestions;
    }

    public function getExamIds($exams) {
        $examIds = array();
        foreach ($exams as $exam) {
            $examIds[] = $exam->getQuizId();
        }
        return $examIds;
    }

    public function findUserAttempts($currentUserId, $examIds) {
        if (count($examIds) > 0) {
            $examAttempts = $this->getEntityRepository()
                    ->findUserAttempts($currentUserId, $examIds);
        } else {
            $examAttempts = array();
        }

        return $examAttempts;
    }

    // Override
    public function completeAttempt($examAttempt) {
        $examAttempt->setEndTime(new \Sysco\Aurora\Stdlib\DateTime());
        parent::completeAttempt($examAttempt);
    }

    private function getNewQuestionAndAnswer($examAttempt, $question) {
        $questionAndAnswer = new \Quiz\Entity\ExamAttemptQuestionAndAnswers();
        $questionAndAnswer->setQuizAttempt($examAttempt);
        $questionAndAnswer->setQuestion($question);
        return $questionAndAnswer;
    }

    public function createReportTable($examAttempts) {
        $headerValues = $this->createReportHeaderValues();
        $dataTable = array();
        foreach ($examAttempts as $examAttempt) {
            $dataRow = array(
                $examAttempt->getUser()->getDisplayName(),
                $examAttempt->getQuiz()->getName(),
                $examAttempt->getAttemptDate(),
                $examAttempt->getTimeUsed(),
                $examAttempt->getResult(),
                $this->translate($examAttempt->getStatusForPrint())
            );
            array_push($dataTable, $dataRow);
        }
        $title = $this->translate("Exam Attempts report");
        $reportTable = new \Application\Entity\ReportTable($title, $headerValues, $dataTable);
        return $reportTable;
    }

    private function createReportHeaderValues() {
        return array(
            $this->translate('User'),
            $this->translate('Exam'),
            $this->translate('Date'),
            $this->translate('Time used'),
            $this->translate('Result'),
            $this->translate('Status'));
    }

    protected function getEntityRepository() {
        return $this->getRepository('Quiz\Entity\ExamAttempt');
    }

    protected function getExamRepository() {
        return $this->getEntityManager()->getRepository(
                        'Quiz\Entity\Exam');
    }

}
