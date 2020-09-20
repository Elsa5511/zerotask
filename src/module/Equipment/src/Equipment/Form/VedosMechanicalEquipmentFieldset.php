<?php


namespace Equipment\Form;


use Doctrine\Common\Persistence\ObjectManager;

class VedosMechanicalEquipmentFieldset extends EquipmentFieldset {
    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $optionValues, $equipmentId) {
        $this->objectManager = $objectManager;

        parent::__construct($objectManager, $optionValues, $equipmentId);
    }

    protected function addChildFields() {
        $this->add(array(
            'name' => 'wll',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => 'WLL',
            ),
            'options' => array(
                'label' => 'WLL',
            ),
        ));

        $this->add(array(
            'name' => 'length',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Length'),
            ),
            'options' => array(
                'label' => $this->translate('Length'),
            ),
        ));

        $this->add(array(
            'name' => 'materialQuality',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Material quality'),
            ),
            'options' => array(
                'label' => $this->translate('Material quality'),
            ),
        ));

        $this->add(array(
            'name' => 'standard',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Standard'),
            ),
            'options' => array(
                'label' => $this->translate('Standard'),
            ),
        ));

        $this->add(array(
            'name' => 'typeApproval',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Type approval'),
            ),
            'options' => array(
                'label' => $this->translate('Type approval'),
            ),
        ));

        $this->add(array(
            'name' => 'controlOrgan',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'id' => 'organization_id',
                'required' => false
            ),
            'options' => array(
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\Organization',
                'empty_option' => $this->translate('Choose an organization'),
                'label' => $this->translate('Control organ'),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findControlOrgans',
                )
            ),
        ));
    }

    public function getInputFilterSpecification() {
        $numberErrormessage = $this->getTranslator()->translate(
            'The input should be a number');

        $parentSpecification = parent::getInputFilterSpecification();
        $ownSpesification = array(
            'wll' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => "Zend\I18n\Validator\Float",
                        'options' => array(
                            'locale' => 'en_US',
                            'encoding' => self::DEFAULT_ENCODING,
                            'messages' => array(
                                \Zend\I18n\Validator\Float::NOT_FLOAT => $numberErrormessage
                            )
                        )
                    ),
                )
            ),
            'length' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => "Zend\I18n\Validator\Float",
                        'options' => array(
                            'locale' => 'en_US',
                            'encoding' => self::DEFAULT_ENCODING,
                            'messages' => array(
                                \Zend\I18n\Validator\Float::NOT_FLOAT => $numberErrormessage
                            )
                        )
                    )
                )
            ),
            'materialQuality' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 255),
                ),
            ),
            'standard' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 255),
                ),
            ),
            'typeApproval' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 255),
                ),
            ),
            'controlOrgan' => array(
                'required' => false,
            ),
        );
        return array_merge($parentSpecification, $ownSpesification);
    }
}