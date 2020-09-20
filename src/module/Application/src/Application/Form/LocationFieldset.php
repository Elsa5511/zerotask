<?php
namespace Application\Form;

use Application\Entity\LocationTaxonomy;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class LocationFieldset extends AbstractBaseFieldset {

    public function __construct(ObjectManager $objectManager, 
        $name = 'location') {
        parent::__construct($name);
        
        $this->setObjectManager($objectManager);
        $this->setHydrator(
            new DoctrineHydrator($objectManager, 
                'Application\Entity\LocationTaxonomy', false))->setObject(
            new LocationTaxonomy());
        
        $this->add(
            array(
                'name' => 'parent',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'empty_option' => $this->translate('None'),
                    'label' => $this->translate('Parent'),
                    'object_manager' => $this->getObjectManager(),
                    'target_class' => 'Application\Entity\LocationTaxonomy',
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
                )
            ));
        
        $this->add(
            array(
                'name' => 'name',
                'type' => 'text',
                'attributes' => array(
                    'required' => 'required'
                ),
                'options' => array(
                    'label' => $this->translate('Name')
                )
            ));
        
        $this->add(
            array(
                'name' => 'description',
                'type' => 'textarea',
                'options' => array(
                    'label' => $this->translate('Description')
                )
            ));
        
        $this->add(
            array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => $this->translate('Save changes'),
                    'class' => 'btn btn-primary'
                )
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
            'parent' => array(
                'required' => false
            ),
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => $this->getNameValidator()
            ),
            'description' => array(
                'required' => false,
                'filters' => $this->getTextFilters()
            ),            
        );
        return $inputFilter;
    }

    private function getNameValidator() {
        $validation = array(
            $this->getNotEmptyValidator(),
            $this->getLengthValidator(),
            $this->getOnlyLettersNumbersValidator(),
        );
        return $validation;
    }
}
