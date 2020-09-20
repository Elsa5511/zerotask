<?php

namespace Quiz\Form;

use Application\Form\AbstractBaseFieldset;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Quiz\Entity\Option;

class OptionFieldset extends AbstractBaseFieldset
{

    public function __construct($objectManager, $translator)
    {        
        parent::__construct("option");
        $this->setTranslator($translator);
        $this->setHydrator(new DoctrineHydrator($objectManager, 'Quiz\Entity\Option'))->setObject(new Option());

        $this->add(array(
            'name' => 'optionId',
            'type' => 'hidden',
        ));

        $this->add(
                array(
                    'name' => 'optionText',
                    'type' => 'text',
                    'attributes' => array(
                    'required' => 'required'
                    ),
                    'options' => array(
                        'label' => $this->translate('Option text')
                    )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'isCorrect',
            'options' => array(
                'label' => $this->translate('Correct answer'),
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));
        
        $this->add(array(
            'name' => 'delete',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'del-opt-btn',
                'class' => 'btn-danger',
                'value' => $this->translate('Delete option')
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'optionText' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 500)
                )
            ),
            'isCorrect' => array(
                'required' => false,
            ),
        );
        return $inputFilter;
    }
}
