<?php

namespace Quiz\Service;

class QuizAttemptStatusOverviewCalculator {

    private $quizAnswerValidator;
    private $questionsAnswered = 0;
    private $totalPossibleResult = 0;
    private $correctAnswers = 0;
    private $totalResult = 0;
    private $totalNoOfQuestions = 0;
    private $requiredForPass = 0;

    public function createStatusOverview($quizAttempt) {
        $this->quizAnswerValidator = new \Quiz\Service\QuizAnswerValidator();
        $questionsAndAnswers = $quizAttempt->getQuestionsAndAnswers();
        $this->totalNoOfQuestions = count($questionsAndAnswers);
        $this->requiredForPass = $quizAttempt->getQuiz()->getRequiredForPass();

        foreach ($questionsAndAnswers as $questionAndAnswers) {
            $this->calculateResultsFor($questionAndAnswers);
        }

        return $this->createAndPopulateStatusOverview();
    }

    private function calculateResultsFor($questionAndAnswers) {
        $question = $questionAndAnswers->getQuestion();
        $selectedOptions = $questionAndAnswers->getSelectedOptions();
        $questionWeight = $question->getWeight();
        $this->totalPossibleResult += 1 * $questionWeight;

        if (count($selectedOptions) > 0) {
            $this->questionsAnswered++;

            if ($this->quizAnswerValidator->answersAreCorrect($questionAndAnswers)) {
                $this->correctAnswers++;
                $this->totalResult += 1 * $questionWeight;
            }
        }
    }

    private function createAndPopulateStatusOverview() {
        $statusOverview = new QuizAttemptStatusOverview();
        $statusOverview->setTotalNoOfQuestions($this->totalNoOfQuestions);
        $statusOverview->setQuestionsAnswered($this->questionsAnswered);
        $statusOverview->setCorrectAnswers($this->correctAnswers);
        $statusOverview->setTotalResult($this->totalResult);
        $statusOverview->setTotalPossibleResult($this->totalPossibleResult);
        $statusOverview->setRequiredScorePercentage($this->requiredForPass);
        return $statusOverview;
    }

}
