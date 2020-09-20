<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;

class EquipmentInstanceBaseFieldset extends AbstractBaseFieldset
{
    public function __construct(ObjectManager $objectManager, $translator, $name = 'equipment-instance')
    {
        parent::__construct($name);

        $this->add(
                array(
                    'name' => 'serialNumber',
                    'type' => 'text',
                    'attributes' => array(
                        'required' => 'required'
                    ),
                    'options' => array(
                        'label' => $translator->translate('Serial number')
                    )
        ));

        $this->add(
                array(
                    'name' => 'regNumber',
                    'type' => 'text',
                    'options' => array(
                        'label' => $translator->translate('Reg number')
                    )
        ));

        $this->add(
                array(
                    'name' => 'batchNumber',
                    'type' => 'text',
                    'options' => array(
                        'label' => $translator->translate('Batch number')
                    )
        ));

        $this->add(
                array(
                    'name' => 'certificateNumber',
                    'type' => 'text',
                    'options' => array(
                        'label' => $translator->translate('Certificate number')
                    )
        ));

        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'usageStatus',
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Equipment\Entity\EquipmentInstanceTaxonomy',
                        'empty_option' => $translator->translate('Choose an usage status'),
                        'property' => 'name',
                        'label' => $translator->translate('Usage status'),
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('type' => 'usage'),
                                'orderBy' => array(
                                    'name' => 'ASC'
                                )
                            )
                        )
                    ),
                )
        );

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'location',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\LocationTaxonomy',
                    'empty_option' => $translator->translate('Choose a location'),
                    'label' => $translator->translate('Location'),
                    'is_method' => true,
                    'property' => 'slug',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findAll',
                        'params' => array(
                            'orderBy' => array(
                                'slug' => 'ASC'
                            )
                        )
                    )
                ),
            )
        );

        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'owner',
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Application\Entity\Organization',
                        'empty_option' => $translator->translate('Choose an organization'),
                        'property' => 'name',
                        'label' => $translator->translate('Owner'),
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('status' => 'active', 'type' => 'owner'),
                                'orderBy' => array(
                                    'name' => 'ASC'
                                )
                            )
                        )
                    ),
                )
        );


        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'vendor',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\Organization',
                    'empty_option' => $this->translate('Choose an organization'),
                    'property' => 'name',
                    'label' => $this->translate('Vendor'),
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'vendor'),
                            'orderBy' => array(
                                'name' => 'ASC'
                            )
                        )
                    )
                )
            )
        );

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'manufacturer',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\Organization',
                    'empty_option' => $this->translate('Choose an organization'),
                    'property' => 'name',
                    'label' => $this->translate('Producer'),
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'producer'),
                            'orderBy' => array(
                                'name' => 'ASC'
                            )
                        )
                    )
                )
            )
        );


        $this->add(
            array(
                'name' => 'productionDate',
                'type' => 'date',
                'attributes' => array(
                    'id' => 'production-date',
                ),
                'options' => array(
                    'label' => $translator->translate('Production date')
                )
            ));

        $this->add(
                array(
                    'name' => 'purchaseDate',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'purchase-date',
                    ),
                    'options' => array(
                        'label' => $translator->translate('Purchase date')
                    )
        ));

        $this->add(
                array(
                    'name' => 'technicalLifetime',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'technical-lifetime',
                    ),
                    'options' => array(
                        'label' => $translator->translate('Technical lifetime')
                    )
        ));

        $this->add(
                array(
                    'name' => 'guaranteeTime',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'guarantee-time',
                    ),
                    'options' => array(
                        'label' => $translator->translate('Guarantee time')
                    )
        ));

        $this->add(
                array(
                    'name' => 'receptionControl',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'reception-control',
                    ),
                    'options' => array(
                        'label' => $translator->translate('Reception control')
                    )
        ));

        $this->add(
                array(
                    'name' => 'firstTimeUsed',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'first-time-used',
                    ),
                    'options' => array(
                        'label' => $translator->translate('First time used')
                    )
        ));

        $this->add(
                array(
                    'name' => 'periodicControlDate',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'periodic-control-date',
                    ),
                    'options' => array(
                        'label' => $translator->translate('Periodic control date'),
                        'description' => $translator->translate('Control interval (Days): ')
                    )
        ));

        $this->addChildFields();

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'visualControl',
            'options' => array(
                'label' => 'Visual control',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));

        $this->add(
                array(
                    'name' => 'orderNumber',
                    'type' => 'text',
                    'options' => array(
                        'label' => $translator->translate('Order number')
                    )
        ));

        $this->add(array(
                'name' => 'price',
                'type' => 'text',
                'options' => array(
                    'label' => $translator->translate('Price')
                )
            )
        );

        $this->add(
                array(
                    'name' => 'rfid',
                    'type' => 'text',
                    'attributes' => array(
                        'maxlength' => 100
                    ),
                    'options' => array(
                        'label' => $translator->translate('RFID')
                    )
        ));

        $this->add(array(
            'name' => 'remarks',
            'type' => 'textarea',
            'attributes' => array(
            ),
            'options' => array(
                'label' => $translator->translate('Remarks'),
            ),
        ));

        $this->add(array(
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
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $translator->translate('Save changes'),
                'class' => 'btn btn-primary'
            ),
        ));
    }

    protected function addChildFields() {
    }

    /**
     * Define InputFilterSpecifications
     *
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $iFilter = array(
            'serialNumber' => array(
                'required' => true,
                'filters' => $this->getTextFilters()
            ),
            'usageStatus' => array(
                'required' => false,
            ),
            'location' => array(
                'required' => false,
            ),
            'owner' => array(
                'required' => false,
            ),
            'price' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNumericalValuesValidator(),
                    $this->getBetweenValuesValidator(0, 99999999)
                ),
            ),
            'rfid' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 100)
                ),
            ),
            'productionDate' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'purchaseDate' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'technicalLifetime' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'guaranteeTime' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'receptionControl' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'firstTimeUsed' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'periodicControlDate' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'vendor' => array(
                'required' => false,
            ),
            'manufacturer' => array(
                'required' => false,
            ),
        );
        return $iFilter;
    }
}

