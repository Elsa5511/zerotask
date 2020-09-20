<?php

namespace LadocDocumentation\Form;


use Application\Form\AbstractBaseFieldset;
use LadocDocumentation\Entity\LadocDocumentation;

class CarrierWeightAndDimensionsFieldset extends AbstractBaseFieldset {
    private $translator;

    public function __construct($translator, $referencedFieldsets) {
        $this->translator = $translator;
        parent::__construct('weight-and-dimensions');

        $this->add(array(
            'name' => 'direction',
            'type' => 'hidden',

            'attributes' => array(
                'id' => 'direction-field',
                'value' => LadocDocumentation::DIRECTION_NEXT,
            )
        ));

        $ownWeightFieldset = $referencedFieldsets['ownWeight'];
        $ownWeightFieldset->setLabel('Own weight');
        $ownWeightFieldset->setName('ownWeight');
        $this->add($ownWeightFieldset);

        $technicalWeightFieldset = $referencedFieldsets['technicalWeight'];
        $technicalWeightFieldset->setLabel('Technical weight');
        $technicalWeightFieldset->setName('technicalWeight');
        $this->add($technicalWeightFieldset);

        $this->add(array(
            'name' => 'weightAdditionalInfo',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translator->translate("Weight - Additional information")
            ),
            'attributes' => array(
                'id' => 'weight-additional-info',
                'required' => false,
                'rows' => '3',
                'class' => 'span8 richtext-field'
            )
        ));


        $ownDimensionsFieldset = $referencedFieldsets['ownDimensions'];
        $ownDimensionsFieldset->setLabel('Carrier');
        $ownDimensionsFieldset->setName('ownDimensions');
        $this->add($ownDimensionsFieldset);

        $loadingPlanDimensionsFieldset = $referencedFieldsets['loadingPlanDimensions'];
        $loadingPlanDimensionsFieldset->setLabel('Loading plan');
        $loadingPlanDimensionsFieldset->setName('loadingPlanDimensions');
        $this->add($loadingPlanDimensionsFieldset);


        $this->add(array(
            'name' => 'dimensionsAdditionalInfo',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translator->translate("Dimensions - Additional information")
            ),
            'attributes' => array(
                'id' => 'dimensions-additional-info',
                'required' => false,
                'rows' => '3',
                'class' => 'span8 richtext-field'
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
            'weightAdditionalInfo' => array(
                'required' => false,
                //'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 2048)
                )
            ),
            'dimensionsAdditionalInfo' => array(
                'required' => false,
                //'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 2048)
                )
            ),
        );
    }
}