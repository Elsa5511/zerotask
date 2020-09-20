<?php

namespace Equipment\Form;

use Application\Service\UserService;

class SearchAdvancedForInstanceControlFieldset extends EquipmentInstanceAdvancedSearchBaseFieldset {

    protected $objectManager;

    public function __construct($objectManager, $translator, $application) {
        $this->objectManager = $objectManager;

        parent::__construct('equipment_control', $objectManager, $translator, $application);


        $this->add(array(
            'type' => 'select',
            'name' => 'controlType',
            'options' => array(
                'value_options' => array(
                    'periodic' => $translator->translate('Periodic'),
                    'visual' => $translator->translate('Visual')
                ),
            ),
        ));

        $this->add(array(
            'type' => 'select',
            'name' => 'periodType',
            'options' => array(
                'value_options' => array(
                    'future' => $translator->translate('Future'),
                    'historical' => $translator->translate('Historical'),
                )
            ),
            'attributes' => array(
                'id' => 'period_type',
            )
        ));

        $this->add(array(
                'name' => 'fromDate',
                'type' => 'date',
                'attributes' => array(
                    'class' => 'search-date'
                )
            )
        );

        $this->add(array(
                'name' => 'toDate',
                'type' => 'date',
                'attributes' => array(
                    'class' => 'search-date',
                )
            )
        );

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'registeredBy',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\User',
                    'empty_option' => $translator->translate('Choose a user'),
                    'is_method' => true,
                    'property' => 'displayName',
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array(
                                'state' => UserService::USER_STATE_ACTIVE
                            ),
                            'orderBy' => array(
                                'displayName' => 'ASC'
                            )
                        )
                    )
                ),
                'attributes' => array(
                    'id' => 'user',
                    'class' => 'control-spesific'
                )
            )
        );

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'controlStatus',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Equipment\Entity\PeriodicControlTaxonomy',
                    'empty_option' => $this->translate('Choose a control status'),
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('type' => 'approval'),
                            'orderBy' => array(
                                'name' => 'ASC'
                            )
                        )
                    )
                ),
                'attributes' => array(
                    'id' => 'control_status',
                    'class' => 'control-spesific'
                )
            )
        );

        $this->add(array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'expertiseOrgan',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\Organization',
                    'empty_option' => $this->translate('Choose an expertise organ'),
                    'property' => 'name',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array('status' => 'active', 'type' => 'expertise_organ'),
                            'orderBy' => array(
                                'name' => 'ASC'
                            )
                        )
                    )
                ),
                'attributes' => array(
                    'id' => 'expertise_organ',
                    'class' => 'control-spesific',
                )
            )
        );
    }
}
