<?php

namespace Certification\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Sysco\Aurora\Form\Fieldset;

class CertificationSearchFieldset extends Fieldset
{

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $translator)
    {

        parent::__construct('certification-search');

        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'equipment',
                    'attributes' => array(
                        'id' => 'equipment',
                        'multiple' => 'multiple',
                        'data-placeholder' => $translator->translate('Choose an equipment'),
                    ),
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Equipment\Entity\Equipment',
                        'property' => 'title',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findAll',
                            'params' => array(
                                'orderBy' => array('name' => 'ASC'),
                            ),
                        ),
                        
                    ),
                )
        );
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'valid',            
            'options' => array(
                'value_options' => array(
                    '1' => $this->translate('Valid'),
                    '0' => $this->translate('Not valid'),
                ),
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'theoryPassed',            
            'options' => array(
                'value_options' => array(
                    '1' => $this->translate('Passed'),
                    '0' => $this->translate('Not passed'),
                ),
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'practicalPassed',            
            'options' => array(
                'value_options' => array(
                    '1' => $this->translate('Passed'),
                    '0' => $this->translate('Not passed'),
                ),
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array();
    }

}