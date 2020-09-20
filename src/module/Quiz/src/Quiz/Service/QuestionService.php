<?php

namespace Quiz\Service;

use Application\Service\AbstractBaseService;
use Application\Service\CannotDeleteException;
use Application\Utility\Image;
use Quiz\Entity\Question;
use Quiz\Entity\QuizAttempt;

class QuestionService extends AbstractBaseService
{

    const PATH_QUESTION_CONTENT = './public/content/question/';

    protected function getEntityRepository()
    {
        return $this->getRepository('Quiz\Entity\Question');
    }
    
    public function getQuestionsByExercise($exerciseId)
    {
        return $this->getEntityRepository()->findBy(array('exercise' => $exerciseId));
    }    

    public function removeQuestionImage($image)
    {
        if ($image !== '' && $image !== null) {
            $source = self::PATH_QUESTION_CONTENT . $image;
            if (file_exists($source)) {
                unlink($source);
            }
        }
    }

    public function persistData($question, $image, $prevQuestionNumber)
    {
        $newImageIsUploaded = !empty($image['tmp_name']);
        if ($newImageIsUploaded) {
            $resizedImage = $this->resizeImage($image, $question->getImage());
            $question->setImage($resizedImage);
        }

        $this->updateSortingOnAdd($prevQuestionNumber, $question);
        parent::persist($question);        
        
        return $question->getQuestionId();
    }
    
    private function updateSortingOnAdd($prevQuestionNumber, $question)
    {
        if($prevQuestionNumber > 0) {
            $questions = $question->getExercise()->getQuestions();
            $questionSlice = $questions->slice($prevQuestionNumber);
            $question->setOrderNumber(++$prevQuestionNumber);
            foreach ($questionSlice as $questionElement) {
                $questionElement->setOrderNumber(++$prevQuestionNumber);
                $this->getEntityManager()->persist($questionElement);
            }
        }
    }
    
    private function updateSortingOnDelete($deletedQuestionNumber, $questions)
    {
        if($deletedQuestionNumber > 0) {
            foreach ($questions as $questionElement) {
                $questionOrderNumber = $questionElement->getOrderNumber();
                if($questionOrderNumber > $deletedQuestionNumber) {
                    $questionElement->setOrderNumber($questionOrderNumber - 1);
                }                
                $this->getEntityManager()->persist($questionElement);
            }
            $this->getEntityManager()->flush();
        }
    }
    
    public function clearOptionsFromDb(){
        $optionsFromDb = $this->getOptionRepository()->findBy(array("question" => null));
        if($optionsFromDb)
            foreach($optionsFromDb as $option)
                parent::remove ($option);
    }

    private function resizeImage($imageData, $currentImage)
    {
        $folderPath = self::PATH_QUESTION_CONTENT;
        $image = new Image();
        if ($currentImage) {
            $image->deleteImage($folderPath . $currentImage);
        }
        $newImage = $image->resizeImage(
                $imageData['tmp_name'], $this->image['width'], $folderPath . $imageData['name']);
        return $newImage;
    }

    public function getNewQuestion($exerciseId)
    {
        $exercise = $this->getExerciseRepository()->find($exerciseId);

        if (!$exercise) {
            throw new \Exception($this->translate('Exercise does not exist.'));
        }

        $question = new Question();
        $question->setExercise($exercise);
        return $question;
    }

    public function deleteById($questionId)
    {
        $question = $this->findById($questionId);

        if ($question) {
            $entitiesRelated = $this->getEntitiesRelated($questionId);            
            $isRelated = count($entitiesRelated) > 0;

            if ($isRelated) {
                $errorMessage = $this->getRelationshipErrorMessage($entitiesRelated);
                throw new CannotDeleteException(
                        $errorMessage);
            }
            
            $exercise = $question->getExercise();
            $questionNumber = $question->getOrderNumber();
            parent::remove($question);
            
            $this->updateSortingOnDelete($questionNumber, $exercise->getQuestions());
            
            return $exercise->getQuizId();
        }

        throw new \Exception($this->translate('Could not delete question.'));
    }
    
    public function isAbleToEditQuestion(Question $question){
        $examAttemptsAndAnswers = $this->getEntityRepository()->getExamAttempts($question->getQuestionId());
        if($examAttemptsAndAnswers){
            foreach($examAttemptsAndAnswers as $item){
                if($item->getQuizAttempt()->getStatus() == QuizAttempt::STATUS_IN_PROGRESS)
                    return false;
            }
        }
        
        return true;
    }
    
    /**
     * 
     * 
     * @param type $entityName
     * @param type $entitiesRelatedByGroup
     * @return string Message for relationships
     */
    protected function getRelationshipErrorMessage($entitiesRelatedByGroup)
    {        
        foreach ($entitiesRelatedByGroup as $entityArray) {
            $size = count($entityArray);
        }
        $firstLineMessage = $this->translate('Question can\'t be deleted because it is related to:');
        $secondLineMessage = sprintf($this->translate("%d exercise attempts"), $size);
        $messageError = $firstLineMessage . " " . $secondLineMessage ;
        
        return $messageError;
    }
    
    private function getExerciseRepository()
    {
        return $this->getRepository('Quiz\Entity\Exercise');
    }
    
    private function getOptionRepository()
    {
        return $this->getEntityManager()->getRepository('Quiz\Entity\Option');
    }

}