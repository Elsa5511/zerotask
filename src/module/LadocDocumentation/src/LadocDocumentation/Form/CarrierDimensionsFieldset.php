<?php

namespace LadocDocumentation\Form;


use Application\Form\AbstractBaseFieldset;

class CarrierDimensionsFieldset extends AbstractBaseFieldset {

    public function __construct($translator, $attachmentFieldset, $idPrefix) {
        parent::__construct('carrier-dimensions');

        $this->add(array(
            'name' => 'length',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Length (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'width',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Width (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'heightWithNoLoad',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Height with no load (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $attachmentFieldset->setTranslator($translator);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'attachments',
            'options' => array(
                'label' => $this->translate('Add attachments'),
                'count' => 1,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $attachmentFieldset
            ),
        ));

        $this->add(array(
            'name' => 'delete',
            'attributes' => array(
                'type' => 'button',
                'id' => $idPrefix . '-delete-attachment',
                'class' => 'btn-danger',
            ),
            'options' => array(
                'label' => $this->translate('Delete attachment')
            )
        ));

        $this->add(array(
            'name' => 'add',
            'attributes' => array(
                'type' => 'button',
                'id' => $idPrefix . '-add-attachment',
                'class' => 'btn-danger',
            ),
            'options' => array(
                'label' => $this->translate('Add attachment')
            )
        ));
    }

    public function getInputFilterSpecification() {
        return array(

            'length' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 30)
                )
            ),
            'width' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'heightWithNoLoad' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
        );
    }
}