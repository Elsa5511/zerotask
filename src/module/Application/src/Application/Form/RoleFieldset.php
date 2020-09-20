<?php

namespace Application\Form;

class RoleFieldset extends AbstractBaseFieldset
{

    protected $entityManager;
    protected $roleId;

    public function getRoleId()
    {
        return $this->roleId;
    }

    public function setRoleId($roleId)
    {
        $this->roleId = $roleId;
        return $this;
    }

    public function __construct($options = array())
    {
        parent::__construct('role', $options);

        $this->setObjectManager($options['object_manager']);
        $this->setRoleId($options['role_id']);

        if ($this->getRoleId()) {
            $roleIdAttributes = array(
                'required' => true,
                'readonly' => 'readonly'
            );
        } else {
            $roleIdAttributes = array(
                'required' => true,
            );
        }
            $this->add(array(
                'name' => 'role_id',
                'type' => 'text',
                'attributes' => $roleIdAttributes,
                'options' => array(
                    'label' => $this->translate('Role ID'),
                )
            ));
        

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'attributes' => array(
                'required' => true
            ),
            'options' => array(
                'label' => $this->translate('Name'),
            )
        ));

        $criteria = array();

        if ($this->getRoleId()) {
            $criteria['roleId'] = $this->getRoleId();
        }

        $this->add(array(
            'name' => 'parent',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false
            ),
            'options' => array(
                'label' => $this->translate('Parent'),
                'empty_option' => $this->translate('None'),
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'Application\Entity\Role',
                'property' => 'name',
                'find_method' => array(
                    'name' => 'getAllowableParents',
                    'params' => array(
                        'criteria' => $criteria
                        /*'orderBy' => array(
                            'name' => 'ASC'
                        )*/
                    )
                )
            )
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
        return array(
            'role_id' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^[a-z_]/',
                            'messages' => array(
                                \Zend\Validator\Regex::NOT_MATCH =>
                                $this->getTranslator()->translate('Only letters and "_" are allowed.')
                            ),
                        )
                    )                    
                )
            ),
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),                
                'validators' => array(
                    $this->getOnlyLettersValidator(),
                )
            ),
            'roleId' => array(
                'required' => false,
            ),
            'parent' => array(
                'required' => false,
            )
        );
    }
}