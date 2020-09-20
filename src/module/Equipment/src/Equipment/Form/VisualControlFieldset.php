<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;

class VisualControlFieldset extends AbstractBaseFieldset
{

    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('visual-control');

        $this->add(
                array(
                    'name' => 'controlDate',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'control-date',
                        'required' => 'true'
                    ),
                    'options' => array(
                        'label' => $this->translate('Control date')
                    )
        ));

        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'controlStatus',
                    'attributes' => array(
                        'required' => true
                    ),
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Equipment\Entity\PeriodicControlTaxonomy',
                        'empty_option' => $this->translate('Choose a control status'),
                        'property' => 'name',
                        'label' => $this->translate('Control status'),
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
                )
        );

        $this->add(
                array(
                    'name' => 'nextControlDate',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'next-control-date',
                        'required' => 'true'
                    ),
                    'options' => array(
                        'label' => $this->translate('Next control date'),
                    )
        ));

        $this->add(array(
            'name' => 'remarks',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Remarks'),
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->translate('Save changes')
            ),
        ));
    }

    /**
     * Define InputFilterSpecifications
     *
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'controlDate' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getDateValidator()
                )
            ),
            'nextControlDate' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getDateValidator()
                )
            ),
            'controlStatus' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),                
            ),
        );
        return $inputFilter;
    }

}