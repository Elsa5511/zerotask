<?php

namespace Quiz\Factory\Service;

use Quiz\Factory\Service\QuizServiceFactory;
use Quiz\Entity\Exercise;

class ExerciseServiceFactory extends QuizServiceFactory
{

    protected function getRepositoryAsString()
    {
        return 'Quiz\Entity\Exercise';
    }

    protected function getNewEntity()
    {
        $exercise = new Exercise();
        return $exercise;
    }

}