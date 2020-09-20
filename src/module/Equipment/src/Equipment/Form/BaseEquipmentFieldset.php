<?php

namespace Equipment\Form;

use Equipment\Entity\Equipment;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Form\AbstractBaseFieldset;

class BaseEquipmentFieldset extends AbstractBaseFieldset {
    protected $objectManager;
    protected $equipmentId;
    protected $mode;

    protected $fieldsToShow;
    protected $fields;
    protected $filters;

    public function __construct(ObjectManager $objectManager, $categoryValues, $equipmentId, $fieldsToShow)
    {
        $this->objectManager = $objectManager;
        $this->equipmentId = $equipmentId;
        $this->mode = $equipmentId > 0 ? 'edit' : 'add';
        $this->fields = array();
        $this->fieldsToShow = $fieldsToShow;

        parent::__construct('equipment');

        $this->setHydrator(new DoctrineHydrator($objectManager, 'Equipment\Entity\Equipment'))
            ->setObject(new Equipment());

        $this->fields['title'] = array(
            'name' => 'title',
            'type' => 'Text',
            'attributes' => array(
                'required' => true,
                'placeholder' => $this->translate('Equipment name'),
            ),
            'options' => array(
                'label' => $this->translate('Equipment name'),
            ),
        );

        $this->fields['equipmentTaxonomy'] = array(
            'name' => 'equipmentTaxonomy',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'multiple' => 'multiple',
                'id' => 'category_id',
                'required' => true
            ),
            'options' => array(
                'label' => $this->translate('Equipment Category'),
                'object_manager' => $objectManager,
                'target_class' => 'Equipment\Entity\EquipmentTaxonomy',
                'value_options' => $categoryValues,
            ),
            'find_method' => array(
                'name' => 'getActive',
            )
        );

        $this->fields['feature_image_file'] = array(
            'name' => 'feature_image_file',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('Featured image'),
            ),
        );

        $this->fields['description'] = array(
            'name' => 'description',
            'type' => 'textarea',
            'attributes' => array(),
            'options' => array(
                'label' => $this->translate('Description'),
            ),
        );

        $this->fields['hasToBeUsedWith'] = array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'hasToBeUsedWith',
            'attributes' => array(
                'multiple' => 'multiple',
            ),
            'options' => array(
                'object_manager' => $objectManager,
                'target_class' => 'Equipment\Entity\Equipment',
                'label' => $this->translate('Has to be used with'),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'excludeEquipment',
                    'params' => array(
                        'equipmentId' => $equipmentId
                    ),
                )
            )
        );

        $this->fields['canBeUsedWith'] = array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'canBeUsedWith',
            'attributes' => array(
                'multiple' => 'multiple',
            ),
            'options' => array(
                'object_manager' => $objectManager,
                'target_class' => 'Equipment\Entity\Equipment',
                'label' => $this->translate('Can be used with'),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'excludeEquipment',
                    'params' => array(
                        'equipmentId' => $equipmentId
                    ),
                )
            )
        );

        $this->fields['controlIntervalByDays'] = array(
            'name' => 'controlIntervalByDays',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->translate('Control Interval days'),
            ),
            'options' => array(
                'label' => $this->translate('Control Interval days'),
            )
        );

        $this->fields['instanceType'] = array(
            'name' => 'instanceType',
            'type' => 'select',
            'attributes' => array(
                'id' => 'status',
                'options' => array(
                    'standard' => $this->translate('Standard'),
                    'container' => $this->translate('Container')
                ),
                'disabled' => $this->mode == 'edit'
            ),
            'options' => array(
                'label' => $this->translate('Equipment instance type')
            ),
        );

        $this->fields['status'] = array(
            'name' => 'status',
            'type' => 'select',
            'attributes' => array(
                'id' => 'status',
                'options' => array(
                    'active' => $this->translate('Active'),
                    'inactive' => $this->translate('Inactive')
                )
            ),
            'options' => array(
                'label' => 'Status'
            ),
        );
    }

    protected function buildFieldset()
    {
        if(is_array($this->fieldsToShow)) {
            foreach($this->fieldsToShow as $fieldToShow)
                if(array_key_exists($fieldToShow, $this->fields))
                    $this->add($this->fields[$fieldToShow]);
        }
    }

    protected function getNecessaryInputFilterSpecifications()
    {
        $necessaryInputFilters = array();

        if(is_array($this->fieldsToShow)) {
            foreach($this->fieldsToShow as $fieldToShow)
                if(array_key_exists($fieldToShow, $this->filters))
                    $necessaryInputFilters[$fieldToShow] = $this->filters[$fieldToShow];
        }

        return $necessaryInputFilters;
    }

    public function getInputFilterSpecification()
    {
        $this->filters = array(
            'title' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(2),
                    $this->getNotEmptyValidator(),
                ),
            ),
            'equipmentTaxonomy' => array(
                'required' => true,
                'validators' => array(
                    $this->getNotEmptyValidator(),
                )
            ),
            'hasToBeUsedWith' => array(
                'required' => false,
            ),
            'canBeUsedWith' => array(
                'required' => false,
            ),
            'controlIntervalByDays' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'GreaterThan',
                        'encoding' => 'UTF-8',
                        'options' => array(
                            'min' => 2,
                            'messages' => array(
                                \Zend\Validator\GreaterThan::NOT_GREATER =>
                                    $this->getTranslator()->translate("The input has to be equal or greater than 3"),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Int',
                        'encoding' => 'UTF-8',
                        'options' => array(
                            'min' => 3,
                            'messages' => array(
                                \Zend\I18n\Validator\IsInt::INVALID =>
                                    $this->getTranslator()->translate("The input does not appear to be an integer"),
                            ),
                        ),
                    ),
                ),
            ),
            'feature_image_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getImageFileValidator(),
                ),
            ),
            'instanceType' => array(
                'required' => $this->mode == 'add'
            )
        );
    }
}