<?php

namespace LadocDocumentation\Form;

use Doctrine\Common\Persistence\ObjectManager;

class CarrierLashingPointFieldset extends PointFieldset {

    public function __construct($objectManager, $translator, $mode = 'add') {
        $elements = array();

        $elements[3] = array(
            'name' => 'ruptureStrength',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Rupture Strength daN")
            ),
            'attributes' => array(
                'maxlength' => 50
            )
        );

        $elements[4] = array(
            'name' => 'lc',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("LC daN")
            ),
            'attributes' => array(
                'maxlength' => 50
            )
        );

        $attachmentFieldset = new PointAttachmentFieldset($objectManager, $mode, 
            'LadocDocumentation\Entity\CarrierLashingPointAttachment', 
            new \LadocDocumentation\Entity\CarrierLashingPointAttachment());
        $attachmentFieldset->setTranslator($translator);     
        $elements[5] = array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'carrierLashingPointAttachments',
            'options' => array(
                'label' => $this->translate('Add attachments'),
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $attachmentFieldset
            ),
        );

        $elements[6] = array(
            'name' => 'add',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'add-point-btn',
                'class' => 'btn-danger',
                'value' => $this->translate('Add attachment')
            ),
            'options' => array(
                'class' => 'btn-danger',
            )
        );

        $elements[7] = array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'multiple' => 'multiple',
                'value' => $this->translate('Save changes')
            ),
        );

        parent::__construct($objectManager, $elements);

        $this->setTranslator($translator);
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = parent::getInputFilterSpecification();

        $inputFilterLoad = array(
            'ruptureStrength' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 50)
                )
            )
        );

        $inputFilter = array_merge($inputFilter, $inputFilterLoad);

        return $inputFilter;
    }
}