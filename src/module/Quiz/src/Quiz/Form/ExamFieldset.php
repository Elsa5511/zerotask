<?php

namespace Quiz\Form;

class ExamFieldset extends QuizFieldset
{

    const MAX_NUMBER_QUESTIONS = 30;
    
    public function __construct($objectManager, $equipmentId, $translator)
    {
        
        parent::__construct("exam");

        $this->setObjectManager($objectManager);

        $this->add(array(
            "name" => "numberOfQuestions",
            "type" => "Number",
            "options" => array(
                "label" => $translator->translate("Number of questions"),
            ),
            "attributes" => array(
                "required" => "true",
                "min" => 1,
                "max" => self::MAX_NUMBER_QUESTIONS,
            )
        ));
        
        $this->add(array(
            "name" => "timeLimit",
            "type" => "Number",
            "options" => array(
                "label" => $translator->translate("Time limit"),
                "description" => "(" . $translator->translate("Minutes") . ")",
            ),
            "attributes" => array(
                "required" => "true",
                "min" => 1,
            )
        ));
        
        $this->add(
                array(
                    'name' => 'baseOnPracticeExercise',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'label' => $translator->translate('Based on exercise'),
                        'empty_option' => $translator->translate('Choose an exercise'),
                        'object_manager' => $objectManager,
                        'target_class' => 'Quiz\Entity\Exercise',
                        'property' => 'name',
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('equipment' => $equipmentId),
                                'orderBy' => array(
                                    'name' => 'ASC'
                                )
                            )
                        )
                    ),
                    "attributes" => array(
                        "id" => "base-exercise",
                        "required" => "true",
                    )
        ));
        
    }

}
