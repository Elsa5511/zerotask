<?php

namespace Quiz\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Sysco\Aurora\Form\Fieldset;

class QuizSearchFieldset extends Fieldset
{

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, array $entityParams, $translator)
    {

        parent::__construct('quiz-search');

        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'user',
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'data-placeholder' => $translator->translate('Choose a user'),
                    ),
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Application\Entity\User',
                        'property' => 'displayName',
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
        
        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'quiz',
                    'attributes' => array(
                        'multiple' => 'multiple',
                        'data-placeholder' => $entityParams['data-placeholder'],
                    ),
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => $entityParams['target_class'],
                        'property' => 'name',
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
        
        $valueOptions = array(
            'passed' => $this->translate('Passed'),
            'failed' => $this->translate('Failed'),
            'in-progress' => $this->translate('In progress'),            
        );
        if(stripos($entityParams['target_class'], "Exam")) {
            $valueOptions = array_merge($valueOptions, 
                    array('not-started' => $this->translate('Not started')));
        }
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'status',
            'options' => array(
                'value_options' => $valueOptions,
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array();
    }

}
