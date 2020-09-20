<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractBaseFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class CarrierLashingEquipmentFieldset extends AbstractBaseFieldset {

    public function __construct($objectManager, $translator, $mode) {
        parent::__construct('point');

        $this->setTranslator($translator);

        $this->add(array(
            'name' => 'description',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Description")
            ),
            'attributes' => array(
                'required' => true,
                'maxlength' => 50
            )
        ));

        $this->add(array(
            'name' => 'nsn',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("NATO #")
            ),
            'attributes' => array(
                'maxlength' => 30
            )
        ));

        $this->add(array(
            'name' => 'quantity',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Count")
            ),
            'attributes' => array(
                'maxlength' => 50
            )
        ));

        $this->add(array(
            'name' => 'length',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Length (m)")
            ),
            'attributes' => array(
                'maxlength' => 30
            )
        ));

        $this->add(array(
            'name' => 'lc',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("LC daN")
            ),
            'attributes' => array(
                'maxlength' => 30
            )
        ));

        $attachmentFieldset = new PointAttachmentFieldset($objectManager, $mode, 
            'LadocDocumentation\Entity\CarrierLashingEquipmentAttachment', 
            new \LadocDocumentation\Entity\CarrierLashingEquipmentAttachment());
        $attachmentFieldset->setTranslator($translator);     
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'carrierLashingEquipmentAttachments',
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
                'multiple' => 'multiple',
                'value' => $this->translate('Save changes')
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'description' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'nsn' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 30)
                )
            ),
            'quantity' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'length' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 30)
                )
            ),
            'lc' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 30)
                )
            ),
        );
        return $inputFilter;
    }
}