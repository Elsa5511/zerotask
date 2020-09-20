<?php

namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class SectionFieldset extends AbstractBaseFieldset
{   
    protected $mode;

    public function __construct(ObjectManager $objectManager, $entityPath, $parentOptions, $mode)
    {
        parent::__construct('section_form');

        $this->setHydrator(
                new DoctrineHydrator($objectManager, $entityPath, false));
        $this->mode = $mode;
        
        if(count($parentOptions) > 0) {
            $this->add(
                array(
                    'name' => 'parent',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'empty_option' => $this->translate('None'),
                        'label' => $this->translate('Parent'),
                        'object_manager' => $objectManager,
                        'target_class' => $entityPath,
                        'value_options' => $parentOptions
                    )
                )
            );
        }
        
        
        $this->add(array(
            'name' => 'label',
            'attributes' => array('required' => true),
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Label'),
            ),
        ));

        $this->add(array(
            'name' => 'sectionOrder',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Order'),
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'parent' => array(
                'required' => false
            ),
            'label' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array($this->getLengthValidator(),  $this->getNotEmptyValidator())
            ),
            'sectionOrder' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array($this->getOnlyDigitsValidator())
            )
        );    
    }
}