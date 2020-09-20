<?php
namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;

class CheckoutFieldset extends AbstractBaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('checkout');

        $this->add(array(
            'name' => 'checkinDate',
            'type' => 'date',
            'attributes' => array(
                'id' => 'checkin-date',
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Expected checkin date')
            )
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'organization',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'object_manager' => $objectManager,
                'target_class' => 'Application\Entity\Organization',
                'empty_option' => $this->translate('Choose an organization'),
                'property' => 'name',
                'label' => $this->translate('Organization'),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('status' => 'active'),
                        'orderBy' => array(
                            'name' => 'ASC'
                        )
                    )
                )
            ),
        ));

        $this->add(array(
            'name' => 'contactPerson',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Contact person full name'),
            ),
        ));

        $this->add(array(
            'name' => 'contactPersonPhone',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Phone'),
            ),
        ));

        $this->add(array(
            'name' => 'contactPersonPosition',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Position'),
            ),
        ));

        $this->add(array(
            'name' => 'comment',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Comments'),
            ),
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
        $inputFilter = array(
            'checkinDate' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getDateValidator()
                )
            ),
            'organization' => array(
                'required' => false,
            ),
        );
        return $inputFilter;
    }
}