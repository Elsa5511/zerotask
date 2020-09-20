<?php

namespace LadocDocumentation\Form;


class LoadBasicInformationFieldset extends BasicInformationFieldset {


    function __construct($objectManager, $translator) {
        parent::__construct($objectManager, $translator);

    }

    protected function addChildFieldsAboveApprovedFormsOfTransportation() { }

    protected function addChildFieldsAboveResponsibleOffice() {
        $this->add(array(
            'name' => 'equivalentModels',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Equivalent models")
            ),
            'attributes' => array(
                'required' => false
            )
        ));
    }


    protected function getChildInputFilterSpecification() {
        return array(
            'equivalentModels' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 255)
                )
            ),
        );
    }
}