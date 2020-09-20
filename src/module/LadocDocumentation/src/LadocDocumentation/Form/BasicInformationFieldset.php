<?php

namespace LadocDocumentation\Form;


use Application\Form\AbstractBaseFieldset;

abstract class BasicInformationFieldset extends AbstractBaseFieldset {

    protected abstract function addChildFieldsAboveApprovedFormsOfTransportation();
    protected abstract function addChildFieldsAboveResponsibleOffice();
    protected abstract function getChildInputFilterSpecification();

    public function __construct($objectManager, $translator) {
        parent::__construct('basic-information');

        $this->add(array(
            'name' => 'image-data',
            'type' => 'file',
            'options' => array(
                'label' => $translator->translate("Introduction picture")
            )
        ));

        $this->add(array(
            'name' => 'remove-image',
            'type' => 'hidden',

        ));

        $this->add(array(
            'name' => 'approvedName',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Approved name")
            ),
            'attributes' => array(
                'required' => true
            )
        ));

        $this->add(array(
            'name' => 'colloquialName',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("Colloquial name")
            ),
            'attributes' => array(
                'required' => true
            )
        ));

        $this->addChildFieldsAboveApprovedFormsOfTransportation();

        $this->add(array(
            'name' => 'approvedFormsOfTransportation',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => $translator->translate('Approved for'),
                'object_manager' => $objectManager,
                'target_class' => 'LadocDocumentation\Entity\FormOfTransportation',
                ),
            'attributes' => array(
                'multiple' => 'multiple',
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'stanags',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => $translator->translate('According to STANAGs'),
                'object_manager' => $objectManager,
                'target_class' => 'LadocDocumentation\Entity\Stanag',
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'mlc',
            'type' => 'text',
            'options' => array(
                'label' => $translator->translate("MLC")
            ),
            'attributes' => array(
                'required' => false
            )
        ));

        $this->addChildFieldsAboveResponsibleOffice();

        $this->add(array(
            'name' => 'responsibleOffices',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => $translator->translate('Responsible office'),
                'object_manager' => $objectManager,
                'target_class' => 'LadocDocumentation\Entity\ResponsibleOffice',
                'empty_option' => $this->translate('Choose a responsible office'),
            ),
            'attributes' => array(
                'required' => false,
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

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'approvedName' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 255)
                )
            ),
            'colloquialName' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 255)
                )
            ),
            'approvedFormsOfTransportation' => array(
                'required' => false
            ),
            'stanags' => array(
                'required' => false
            ),
            'mlc' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(6, 6),
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => self::REGEX_ONLY_LETTERS_NUMBERS,
                            'messages' => array(
                                \Zend\Validator\Regex::NOT_MATCH => $this->translate(
                                    'This input contains invalid characters. Only letters and numbers are allowed.')
                            )
                        )
                    )
                )
            ),
            'responsibleOffices' => array(
                'required' => false
            ),
            'image-data' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\File\MimeType',
                        'options' => array(
                            'mimeType' => array(
                                'image/jpg',
                                'image/gif',
                                'image/png',
                                'image/jpeg'
                            ),
                            'messages' => array(
                                \Zend\Validator\File\MimeType::FALSE_TYPE =>
                                    $this->translate('The file must be of type ') . '(jpg, jpeg, gif, png)',
                                \Zend\Validator\File\MimeType::NOT_DETECTED =>
                                    $this->translate('The file was not detected'),
                            )
                        ),
                    ),
                ),
            ),
        );

        $childInputFilter = $this->getChildInputFilterSpecification();
        return array_merge($inputFilter, $childInputFilter);
    }
}