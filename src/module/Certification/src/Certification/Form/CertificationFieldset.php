<?php

namespace Certification\Form;

use Application\Form\AbstractBaseFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class CertificationFieldset extends AbstractBaseFieldset 
{

    const USER_STATE_ACTIVE = 1;

    public function __construct(ObjectManager $objectManager) 
    {

        parent::__construct("certification");
        
        $this->setObjectManager($objectManager);
        
        $this->add(array(
            'name' => 'user',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('User'),
                'empty_option' => $this->translate('Choose a user'),
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\User',
                'property' => 'displayName',
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(
                            'state' => self::USER_STATE_ACTIVE
                        ),
                        'orderBy' => array(
                            'displayName' => 'ASC'
                        )
                    )
                )
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'theoryPassed',
            'attributes' => array(
                'required' => 'true',
            ),
            'options' => array(
                'label' => $this->translate('Theory exam result'),
                'value_options' => array(
                    '1' => $this->translate('Passed'),
                    '0' => $this->translate('Not passed'),
                ),
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'practicalPassed',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Practical exam result'),
                'value_options' => array(
                    '1' => $this->translate('Passed'),
                    '0' => $this->translate('Not passed'),
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'expirationDate',
            'type' => 'date',
            'attributes' => array(
                'class' => 'input-date',
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Expiration date')
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
     * Define Input Filter Specifications
     *
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {

        $inputFilter = array(
            'expirationDate' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getDateValidator()
                )
            )
        );
        return $inputFilter;
    }

}
