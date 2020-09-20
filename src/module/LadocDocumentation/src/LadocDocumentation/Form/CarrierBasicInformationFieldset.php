<?php

namespace LadocDocumentation\Form;


class CarrierBasicInformationFieldset extends BasicInformationFieldset
{
    private $translator;

    function __construct($objectManager, $translator) {
        $this->translator = $translator;
        parent::__construct($objectManager, $translator);

    }

    protected function addChildFieldsAboveApprovedFormsOfTransportation() {
        $this->add(array(
            'name' => 'technicalPayload',
            'type' => 'text',
            'options' => array(
                'label' => $this->translator->translate("Technical payload (kg)")
            )
        ));
    }

    protected function getChildInputFilterSpecification() {
        return array(
            'technicalPayload' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 50)
                )
            )
        );
    }

    protected function addChildFieldsAboveResponsibleOffice() { }
}
