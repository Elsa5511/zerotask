<?php

namespace Quiz\Factory\Service;

use Quiz\Factory\Service\QuizServiceFactory;
use Quiz\Entity\Exam;

class ExamServiceFactory extends QuizServiceFactory
{

    protected function getRepositoryAsString()
    {
        return 'Quiz\Entity\Exam';
    }

    protected function getNewEntity()
    {
        $examEntity = new Exam();
        return $examEntity;
    }

}