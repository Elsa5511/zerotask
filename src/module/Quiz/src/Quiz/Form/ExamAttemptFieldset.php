<?php

namespace Quiz\Form;

use Application\Form\AbstractBaseFieldset;

class ExamAttemptFieldset extends AbstractBaseFieldset
{
    
    public function __construct($objectManager, $application)
    {
        
        parent::__construct("exam-attempt");

        $this->setObjectManager($objectManager);
        
        $this->add(
                array(
                    'name' => 'quiz',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'label' => $this->translate('Exam'),
                        'empty_option' => $this->translate('Choose an exam'),
                        'object_manager' => $this->getObjectManager(),
                        'target_class' => 'Quiz\Entity\Exam',
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('application' => $application),
                                'orderBy' => array(
                                    'name' => 'ASC'
                                )
                            )
                        )
                    ),
                    "attributes" => array(
                        "required" => "true",
                    )
        ));

        // TODO create a findActiveUsers in repo
        $this->add(
                array(
                    'name' => 'user',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'label' => $this->translate('User'),
                        'empty_option' => $this->translate('Choose a user'),
                        'object_manager' => $this->getObjectManager(),
                        'target_class' => 'Application\Entity\User',
                        'property' => 'displayName',
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('state' => \Application\Service\UserService::USER_STATE_ACTIVE),
                                'orderBy' => array(
                                    'displayName' => 'ASC'
                                )
                            )
                        )
                    ),
                    "attributes" => array(
                        "required" => "true",
                    )
        ));

        $this->add(array(
            'name' => 'expirationDate',
            'type' => 'date',
            'options' => array(
                'label' => $this->translate('Expiration date'),
            ),
            'attributes' => array(
                'id' => 'expiration-date',
            )
        ));


        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->translate('Add exam attempt')
            ),
        ));
    }
    
    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'user' => array(
                'required' => true,
            ),
            'quiz' => array(
                'required' => true,
            ),
            'expirationDate' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getDateValidator()
                )
            ),
        );
        return $inputFilter;
    }

}
