<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractBaseFieldset;
use LadocDocumentation\Entity\LadocDocumentation;

class LoadWeightAndDimensionsFieldset extends AbstractBaseFieldset {
    public function __construct($translator, $objectManager, $mode = 'add') {
        parent::__construct('weight-and-dimensions');

        $this->add(array(
            'name' => 'direction',
            'type' => 'hidden',

            'attributes' => array(
                'id' => 'direction-field',
                'value' => LadocDocumentation::DIRECTION_NEXT,
            )
        ));

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
            'name' => 'maxHeightWithOwnWeight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Max height with own weight (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'groundClearanceWithOwnWeight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Ground clearance with own weight (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'ownWeight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Own weight (kg)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'technicalTotalWeight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Technical total weight (kg)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'gravityWithOwnWeight',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Gravity in all 3 axises with own weight")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'gravityWithTotalWeigth',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Gravity in all 3 axises with total weight")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'gaugeOfWheels',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Gauge exterior/interior of wheels or tracks (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'overhangAngle',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Overhang angle front/rear")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'overhang',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Overhang front / wheelbase / overhang rear (mm)")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'additionalInfo',
            'type' => 'textarea',
            'options' => array(
                'label' => $translator->translate("Additional information")
            ),
            'attributes' => array(
                'required' => false,
                'rows' => '3',
                'class' => 'span8 richtext-field'
            )
        ));

        $attachmentFieldset = new PointAttachmentFieldset($objectManager, $mode,
            'LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment',
            new \LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment());
        $attachmentFieldset->setTranslator($translator);

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'attachments',
            'options' => array(
                'label' => $this->translate('Add attachments'),
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $attachmentFieldset
            ),
        ));

        $this->add(array(
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
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'ladoc-continue-button',
                'multiple' => 'multiple',
                'value' => $translator->translate('Continue')
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'length' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'width' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'maxHeightWithOwnWeight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'groundClearanceWithOwnWeight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'ownWeight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'technicalTotalWeight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'gravityWithOwnWeight' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'gravityWithTotalWeigth' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'gaugeOfWheels' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'overhangAngle' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'overhang' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
            'additionalInfo' => array(
                'required' => false,
                //'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 2048)
                )
            )
        );
    }
}