<?php

namespace Quiz\Form;

use Application\Form\AbstractBaseFieldset;

class QuizFieldset extends AbstractBaseFieldset
{

    public function __construct($name)
    {
        parent::__construct($name);

        $this->add(
                array(
                    'name' => 'name',
                    'type' => 'text',
                    'attributes' => array(
                    'required' => 'required'
                    ),
                    'options' => array(
                        'label' => $this->translate('Name')
                    )
        ));

        $this->add(
                array(
                    'name' => 'requiredForPass',
                    'type' => 'Number',
                    "attributes" => array(
                        "required" => "true",
                        "min" => 1,
                        "max" => 100,
                    ),
                    'options' => array(
                        'label' => $this->translate('Score requirement (&#37;)')
                    )
        ));

        $this->add(
                array(
                    'name' => 'introductionText',
                    'type' => 'textarea',
                    'options' => array(
                        'label' => $this->translate('Introduction text')
                    )
        ));
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => $this->getNameValidators()
            ),
            'requiredForPass' => array(
                'required' => true,
                'validators' => $this->getRequiredForPassValidators()
            ),
            'introductionText' => array(
                'required' => false,
                'filters' => $this->getTextFilters()
            ),
        );
        return $inputFilter;
    }

    private function getRequiredForPassValidators()
    {
        $validation = array(
            $this->getNumericalValuesValidator(),
            $this->getBetweenValuesValidator(0, 100)
        );
        return $validation;
    }

    private function getNameValidators()
    {
        $validation = array(
            $this->getNotEmptyValidator(),
            $this->getLengthValidator(1, 255),
        );
        return $validation;
    }

}
