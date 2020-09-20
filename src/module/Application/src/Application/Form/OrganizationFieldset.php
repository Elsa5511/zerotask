<?php

namespace Application\Form;

use Application\Entity\Organization;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class OrganizationFieldset extends AbstractBaseFieldset
{
    public function __construct(ObjectManager $objectManager, $name = 'organization')
    {
        parent::__construct($name);

        $this->setObjectManager($objectManager);
        $this->setHydrator(
                        new DoctrineHydrator($objectManager, 'Application\Entity\Organization', false))
                ->setObject(new Organization());

        $this->add(
                array(
                    'name' => 'type',
                    'type' => 'select',
                    'attributes' => array(
                        'options' => array(
                            'owner' => $this->translate('Owner'),
                            'vendor' => $this->translate('Vendor'),
                            'producer' => $this->translate('Producer'),
                            'expertise_organ' => $this->translate('Expertise organ'),
                            'control_organ' => $this->translate('Control organ')
                        )
                    ),
                    'options' => array(
                        'label' => $this->translate('Type'),
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
                    'name' => 'contactPerson',
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('Contact person')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'email',
                    'type' => 'Zend\Form\Element\Email',
                    'options' => array(
                        'label' => $this->translate('Email')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'phone',
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('Phone Number')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'fax',
                    'type' => 'text',
                    'attributes' => array(
                        'type' => 'text'
                    ),
                    'options' => array(
                        'label' => $this->translate('Fax')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'address',
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('Address')
                    )
        ));

        $this->add(
                array(
                    'name' => 'city',
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('City')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'zip',
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('Zip code')
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'country',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'empty_option' => $this->translate('Choose a country'),
                        'label' => $this->translate('Country'),
                        'object_manager' => $this->getObjectManager(),
                        'target_class' => 'Application\Entity\Country',
                        'property' => 'name',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array(),
                                'orderBy' => array(
                                    'name' => 'ASC'
                                )
                            )
                        )
                    )
        ));
        
        $this->add(
                array(
                    'name' => 'url',
                    'attributes' => array(
                        'id' => 'url',
                        'class'=>'input-medium'
                    ),
                    'type' => 'text',
                    'options' => array(
                        'label' => $this->translate('Url')
                    )
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'attributes' => array(
                'id' => 'status',
                'options' => array(
                    'active' => $this->translate('Active'),
                    'inactive' => $this->translate('Inactive')
                )
            ),
            'options' => array(
                'label' => 'Status'
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->translate('Save changes'),
                'class' => 'btn btn-primary'
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
        $iFilter = array(
            'name' => array(
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getLengthValidator(),
                )
            ),
            'email' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => $this->getValidator('email')
            ),
            'description' => array(
                'required' => false,
                'filters' => $this->getTextFilters()
            ),
            'address' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator($min = 0, $max = 255)
                )
            ),
            'zip' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator($min = 2, $max = 30)
                )
            ),
            'city' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getLengthValidator(),
                )
            ),
            'phone' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator($min = 4, $max = 50)
                )
            ),
            'fax' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator($min = 4, $max = 50)
                )
            ),
            'url' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    array(
                        'name' => 'Hostname',
                        'options' => array(
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'messages' => array(
                                \Zend\Validator\Hostname::INVALID =>
                                $this->getTranslator()->translate("Invalid url"),
                                \Zend\Validator\Hostname::INVALID_HOSTNAME =>
                                $this->getTranslator()->translate("The input does not match the expected structure for a url"),
                                \Zend\Validator\Hostname::INVALID_LOCAL_NAME =>
                                $this->getTranslator()->translate("The input does not appear to be a valid network name"),
                            ),
                        ),
                    ),
                ),
            ),
            'contactPerson' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getLengthValidator(),
                )
            ),
            'country' => array(
                'required' => false,
            ),
        );
        return $iFilter;
    }

    private function getValidator($field)
    {
        $validation = array(
            $this->getNotEmptyValidator(),
            $this->getLengthValidator(4, 100)
        );

        if ($field === 'email') {
            $errorMessage = $this->getTranslator()->translate(
                    "The input is not a valid email address");
            $emailValidator = array(
                'name' => "EmailAddress",
                'options' => array(
                    'domain' => false,
                    'messages' => array(
                        \Zend\Validator\EmailAddress::INVALID => $errorMessage
                    )
                )
            );
            array_push($validation, $emailValidator);
        }
        return $validation;
    }
}