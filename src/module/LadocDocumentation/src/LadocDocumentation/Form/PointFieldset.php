<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractBaseFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class PointFieldset extends AbstractBaseFieldset {

    public function __construct($objectManager, $extraElements = array()) {
        parent::__construct('point');

        $elements = array();

        $elements[] = array(
            'name' => 'placement',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Placement")
            ),
            'attributes' => array(
                'required' => true,
                'maxlength' => 50
            )
        );

        $elements[] = array(
            'name' => 'description',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Description")
            ),
            'attributes' => array(
                'required' => true,
                'maxlength' => 50
            )
        );

        $elements[] = array(
            'name' => 'quantity',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Count")
            ),
            'attributes' => array(
                'required' => true,
                'maxlength' => 50
            )
        );

        if(count($extraElements) > 0)
            $this->insertElementsToArray($elements, $extraElements);

        $this->addElementsToFieldset ($elements);
    }

    private function insertElementsToArray (array &$mainArray, array $elementsToInsert)
    {
        foreach($elementsToInsert as $pos => $element) {
            array_splice($mainArray, $pos, 0, array($element));
        }
    }

    private function addElementsToFieldset (array $elements) 
    {
        foreach($elements as $element) {
            $this->add($element);
        }
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'placement' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'description' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'quantity' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'lc' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 50)
                )
            ),
        );
        return $inputFilter;
    }
}