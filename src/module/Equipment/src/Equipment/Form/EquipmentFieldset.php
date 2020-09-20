<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;

class EquipmentFieldset extends BaseEquipmentFieldset {
    const REGEX_NSN = "/^.{9}$|^.{13}$/";

    protected $application;

    public function __construct(ObjectManager $objectManager, $categoryValues, $equipmentId, $fieldsToShow, $application = '') {
        parent::__construct($objectManager, $categoryValues, $equipmentId, $fieldsToShow);

        $this->application = $application;

        $this->fields['featureOverrides'] = array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'featureOverrides',
            'attributes' => array(
                'multiple' => 'multiple',
            ),
            'options' => array(
                'object_manager' => $objectManager,
                'target_class' => 'Application\Entity\Feature',
                'label' => $this->translate('Feature override'),
                'is_method' => true,
            )
        );

        $this->fields['nsn'] = array(
            'name' => 'nsn',
            'type' => 'Text',
            'attributes' => array(
                'id' => "nsn",
                //'maxlength' => 13,
                'required' =>false
            ),
            'options' => array(
                'label' => 'NATO #',
            ),
        );
        if($application !== 'ladoc')
            $this->fields['nsn']['attributes']['maxlength'] = 13;

        $this->fields['sap'] = array(
            'name' => 'sap',
            'type' => 'Text',
            'attributes' => array(
                //'maxlength' => 8
            ),
            'options' => array(
                'label' => 'SAP #',
            ),
        );
        if($application !== 'ladoc')
            $this->fields['sap']['attributes']['maxlength'] = 8;

        $this->fields['vendorPart'] = array(
            'name' => 'vendorPart',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->translate('Vendor part'),
            ),
            'options' => array(
                'label' => $this->translate('Vendor part'),
            )
        );

        //Vedos Mechanical fields
        $this->fields['wll'] = array(
            'name' => 'wll',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('WLL (kg)'),
            ),
            'options' => array(
                'label' => $this->translate('WLL (kg)'),
            ),
        );

        $this->fields['length'] = array(
            'name' => 'length',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Length (cm)'),
            ),
            'options' => array(
                'label' => $this->translate('Length (cm)'),
            ),
        );

        $this->fields['materialQuality'] = array(
            'name' => 'materialQuality',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Material quality'),
            ),
            'options' => array(
                'label' => $this->translate('Material quality'),
            ),
        );

        $this->fields['standard'] = array(
            'name' => 'standard',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Standard'),
            ),
            'options' => array(
                'label' => $this->translate('Standard'),
            ),
        );

        $this->fields['typeApproval'] = array(
            'name' => 'typeApproval',
            'type' => 'text',
            'attributes' => array(
                'placeholder' => $this->translate('Type approval'),
            ),
            'options' => array(
                'label' => $this->translate('Type approval'),
            ),
        );

        $this->fields['controlOrgan'] = array(
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
        );

        $this->buildFieldset();
    }

    public function getInputFilterSpecification()
    {
        parent::getInputFilterSpecification();

        $numberErrormessage = $this->getTranslator()->translate(
            'The input should be a number');

        $optionalFilters = array(
            'featureOverrides' => array(
                'required' => false,
            ),
            'sap' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
            ),
            'nsn' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
            ),
            'wll' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => "Zend\I18n\Validator\IsFloat",
                        'options' => array(
                            'locale' => 'en_US',
                            'encoding' => self::DEFAULT_ENCODING,
                            'messages' => array(
                                \Zend\I18n\Validator\IsFloat::NOT_FLOAT => $numberErrormessage
                            )
                        )
                    ),
                    $this->getBetweenValuesValidator(0, 99999999)
                )
            ),
            'length' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => "Zend\I18n\Validator\IsFloat",
                        'options' => array(
                            'locale' => 'en_US',
                            'encoding' => self::DEFAULT_ENCODING,
                            'messages' => array(
                                \Zend\I18n\Validator\IsFloat::NOT_FLOAT => $numberErrormessage
                            )
                        )
                    ),
                    $this->getBetweenValuesValidator(0, 99999999)
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

        if($this->application !== 'ladoc') {
            $optionalFilters['sap']['validators'] = $this->sapValidator($this->equipmentId);
            $optionalFilters['nsn']['validators'] = $this->nsnValidator($this->equipmentId);
        }

        if(is_array($this->filters))
            $this->filters = array_merge_recursive($this->filters, $optionalFilters);

        return $this->getNecessaryInputFilterSpecifications();
    }

    private function sapValidator($equipmentId)
    {
        if ($equipmentId === 0) {
            $excludeArray = array();
        } else {
            $excludeArray = array(
                'field' => 'equipmentId',
                'value' => $this->equipmentId,
            );
        }
        $validator = $this->sapNsnValidator($excludeArray, "SAP #", 'sap');

        array_push($validator, array(
            'name' => 'Digits',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\Digits::NOT_DIGITS =>
                        $this->getTranslator()->translate("The input must contain only digits")
                )
            ),
        ));
        array_push($validator, $this->getLengthValidator(8, 8));
        return $validator;
    }

    private function nsnValidator($equipmentId)
    {
        if ($equipmentId === 0) {
            $excludeValue = '1';
        } else {
            $excludeValue = $this->equipmentId;
        }
        $excludeArray = array(
            'field' => 'equipmentId',
            'value' => $excludeValue,
        );
        $validators = $this->sapNsnValidator($excludeArray, "NATO #", 'nsn');
        array_push($validators, array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => self::REGEX_NSN,
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH =>
                        $this->getTranslator()->translate('NATO no. must be 9 or 13 characters.')
                )
            )
        ));
//        array_push($validators, $this->getLengthValidator(9, 16));
        return $validators;
    }

    private function sapNsnValidator($excludeArray, $label, $field)
    {
        $format = $this->getTranslator()->translate("%s is already registered");
        $noObjectMessage = sprintf($format, $label);

        return array(
            $this->getNotEmptyValidator(),
            array(
                'name' => 'Application\Validator\NoObjectExists',
                'options' => array(
                    'object_repository' => $this->objectManager->getRepository('Equipment\Entity\Equipment'),
                    'fields' => $field,
                    'exclude' => $excludeArray,
                    'messages' => array(
                        \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND => $noObjectMessage
                    )
                ),
            ),
            $this->getNotEmptyValidator()
        );
    }

}