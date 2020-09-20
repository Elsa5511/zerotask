<?php

namespace LadocDocumentation\Form;


use Application\Form\AbstractBaseFieldset;

class CarrierWeightFieldset extends AbstractBaseFieldset {
    public function __construct($translator, $attachmentFieldset, $idPrefix) {
        parent::__construct();

        $this->add(array(
            'name' => 'weight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Total weight (kg)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'frontAxle',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Front axle (kg)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'rearAxle',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Rear axle (kg)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'otherAxles',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Other axles (kg)")
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
            'weight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 30)
                )
            ),
            'frontAxle' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'rearAxle' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'otherAxles' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
        );
    }
}