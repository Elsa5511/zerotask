<?php

namespace Quiz\Service;

class QuizAnswerValidator {

    public function answersAreCorrect($questionAndAnswers) {
        $question = $questionAndAnswers->getQuestion();
        $allOptions = $question->getOptions();
        $totalCorrectOptions = $this->findNoOfCorrectOptionsFrom($allOptions);
        $userSelectedOptions = $questionAndAnswers->getSelectedOptions();

        $answersAreCorrect = $this->hasAnswers($userSelectedOptions) &&
                $this->allAnswersAreCorrect($userSelectedOptions) &&
                $this->noOfAnswersIsCorrect($question->getType(), $totalCorrectOptions, $userSelectedOptions);

        return $answersAreCorrect;
    }

    private function findNoOfCorrectOptionsFrom($questionOptions) {
        $noOfCorrectOptions = 0;
        foreach ($questionOptions as $option) {
            if ($option->isCorrect()) {
                $noOfCorrectOptions++;
            }
        }
        return $noOfCorrectOptions;
    }

    private function hasAnswers($userSelectedOptions) {
        return count($userSelectedOptions) > 0;
    }

    private function allAnswersAreCorrect($userSelectedOptions) {
        $allAnswersAreCorrect = true;
        foreach ($userSelectedOptions as $selectedOption) {
            $allAnswersAreCorrect = $allAnswersAreCorrect && $selectedOption->isCorrect();
        }
        return $allAnswersAreCorrect;
    }

    private function noOfAnswersIsCorrect($questionType, $totalCorrectOptions, $userSelectedOptions) {
        $isSingleChoice = $questionType === \Quiz\Entity\Question::TYPE_SINGLE_CHOICE;
        $noOfAnswersIsCorrect = count($userSelectedOptions) == $totalCorrectOptions;
        return $isSingleChoice || $noOfAnswersIsCorrect;
    }

}
