<?php

namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Sysco\Aurora\Form\Fieldset;
use Application\Entity\User;

class ForgotPasswordFieldset extends Fieldset
{

    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;

        parent::__construct('user');

        $this->setHydrator(new DoctrineHydrator($objectManager, 'Application\Entity\User', false))
                ->setObject(new User());        

        $this->add(array(
            'name' => 'username',
            'type' => 'text',
            'attributes' => array(
                'required' => true
            ),
            'options' => array(
                'label' => $this->translate('Username'),
            ),
        ));

        $this->add(array(
            'name' => 'btn_submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->translate('Send'),
                'class' => 'btn btn-primary'
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'username' => array(
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'encoding' => 'UTF-8',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY =>
                                $this->translate("This field is required"),
                            ),
                        ),
                    ),
                    array(
                        'name' => 'Application\Validator\ObjectExistsActive',
                        'options' => array(
                            'object_repository' => $this->objectManager->getRepository('Application\Entity\User'),
                            'fields' => 'username',
                            'messages' => array(
                                \DoctrineModule\Validator\ObjectExists::ERROR_NO_OBJECT_FOUND => 
                                $this->translate("The user has not been registered or has been disabled. Please contact a system administrator.")
                            )
                        ),
                    ),
                ),
            ),
        );
    }

}