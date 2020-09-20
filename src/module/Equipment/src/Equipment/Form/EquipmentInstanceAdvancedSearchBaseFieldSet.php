<?php

namespace Equipment\Form;

use Sysco\Aurora\Form\Fieldset;

abstract class EquipmentInstanceAdvancedSearchBaseFieldset extends Fieldset {

    public function __construct($name, $objectManager, $translator, $application) {
        parent::__construct($name);

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'category',
                'attributes' => array(
                    'id' => 'category'
                ),
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Equipment\Entity\EquipmentTaxonomy',
                    'empty_option' => $translator->translate('Choose a category'),
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active',
                                'type' => 'category',
                                'application' => $application),
                            'orderBy' => array('name' => 'ASC'),
                        ),
                    ),
                ),
            )
        );

        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'equipment',
                'attributes' => array(
                    'id' => 'equipment',
                    'data-controller' => 'equipment',
                    'data-action' => 'search',
                    'class' => 'ajax-chosen'
                ),
                'options' => array(
                    'empty_option' => $translator->translate('Choose an equipment')
                ),
            )
        );

        $this->add(array(
            'name' => 'sap',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'sap',
                'data-mask' => "99999999",
                'maxlength' => 8
            ),
        ));

        $this->add(array(
            'name' => 'serialNumber',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'serialNumber',
                'maxlength' => 50
            ),
        ));

        $this->add(array(
            'name' => 'regNumber',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'regNumber',
                'maxlength' => 50
            ),
        ));

        $this->add(array(
            'name' => 'batchNumber',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'batchNumber',
                'maxlength' => 50
            ),
        ));

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'owner',
                'attributes' => array(
                    'id' => 'owner'
                ),
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class' => 'Application\Entity\Organization',
                    'empty_option' => $translator->translate('Choose an owner'),
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'owner'),
                            'orderBy' => array('name' => 'ASC'),
                        ),
                    ),
                ),
            )
        );

        $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'location',
                'attributes' => array(
                    'data-controller' => 'location',
                    'data-action' => 'search',
                    'class' => 'ajax-chosen'
                ),
                'options' => array(
                    'empty_option' => $translator->translate('Choose a location'),
                ),
            )
        );

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'usageStatus',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Equipment\Entity\EquipmentInstanceTaxonomy',
                    'empty_option' => $translator->translate('Choose an usage status'),
                    'is_method' => true,
                    'property' => 'name',
                    'find_method' => array(
                        'name' => 'findAll',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'usage'),
                            'orderBy' => array('name' => 'ASC'),
                        ),
                    )
                ),
            )
        );
    }

}
