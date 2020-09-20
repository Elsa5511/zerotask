<?php

namespace LadocDocumentation\Form;


use Application\Form\AbstractBaseFieldset;

class DescriptionInformationFieldset extends AbstractBaseFieldset {

	public function __construct($translator) {

        parent::__construct('description_fieldset');

        $this->setTranslator($translator);

        $this->add(array(
            'name' => 'type',
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'attributes' => array(
                'rows' => '3',
                'class' => 'span12 richtext-field'
            )
        ));

        $this->add(array(
            'name' => 'save',
            'type' => 'submit',
            'attributes' => array(
                'class' => 'btn-danger',
                'value' => $this->translate('Update description')
            )
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'description' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 2048)
                )
            )
        );
    }
}