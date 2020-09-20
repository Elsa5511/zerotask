<?php
namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;

class PeriodicControlFieldset extends AbstractBaseFieldset
{
    public function __construct(ObjectManager $objectManager, $currentUserId, $translator)
    {
        parent::__construct('periodic-control');
        
        $this->add(
            array(
                'name' => 'registeredBy',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',                
                'options' => array(
                    'label' => $this->translate('Competent Person'),
                    'object_manager' => $objectManager,
                    'target_class' => 'Application\Entity\User',
                    'property' => 'displayName',
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'findBy',
                        'params' => array(
                            'criteria' => array(
                                'userId' => $currentUserId
                            ),
                        )
                    )
                )
            ));

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
                    'name' => 'nextControlDate',
                    'type' => 'date',
                    'attributes' => array(
                        'id' => 'next-control-date',
                        'required' => 'true'
                    ),
                    'options' => array(
                        'label' => $this->translate('Next control date'),
                        'description' => $translator->translate('Control interval (Days): ')
                    )
        ));
     
     $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'expertiseOrgan',
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Application\Entity\Organization',
                        'empty_option' => $this->translate('Choose an expertise organ'),
                        'property' => 'name',
                        'label' => $this->translate('Expertise organ'),
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
                )
        );
     
     $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'controlStatus',
                    'attributes' => array(
                        'required' => 'true'
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

     $this->add(array(
            'name' => 'comment',
            'type' => 'textarea',            
            'options' => array(
                'label' => $this->translate('Comments'),
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
            'competentPerson' => array(
                'required' => false,
            ),
            'expertiseOrgan' => array(
                'required' => false,
            ),
            'controlStatus' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                )
            ),
        );
        return $inputFilter;
    }
}