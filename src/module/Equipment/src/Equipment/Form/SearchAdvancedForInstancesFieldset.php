<?php

namespace Equipment\Form;

class SearchAdvancedForInstancesFieldset extends EquipmentInstanceAdvancedSearchBaseFieldset {

    protected $objectManager;

    public function __construct($objectManager, $translator, $application) {
        $this->objectManager = $objectManager;

        parent::__construct('equipment_instance', $objectManager, $translator, $application);



        $this->add(array(
            'name' => 'nsn',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'nsn',
                'maxlength' => 16
            ),
        ));

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'vendor',
                'attributes' => array(
                    'id' => 'vendor'
                ),
                'options' => array(
                    'object_manager' => $this->objectManager,
                    'target_class' => 'Application\Entity\Organization',
                    'empty_option' => $translator->translate('Choose a vendor'),
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'vendor'),
                            'orderBy' => array('name' => 'ASC'),
                        ),
                    ),
                ),
            )
        );

        $this->add(array(
            'type' => 'select',
            'name' => 'checkoutStatus',
            'options' => array(
                'empty_option' => $translator->translate('All statuses'),
                'value_options' => array(
                    '1' => $translator->translate('Checked out'),
                    '2' => $translator->translate('Not Checked out')
                ),
            ),
        ));

        $this->add(array(
            'name' => 'orderNumber',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'orderNumber',
                'maxlength' => 50
            ),
        ));

        $this->add(array(
            'name' => 'rfid',
            'type' => 'text',
            'attributes' => array(
                'maxlength' => 100
            )
        ));
    }

    public function getInputFilterSpecification() {
        return array();
    }

}
