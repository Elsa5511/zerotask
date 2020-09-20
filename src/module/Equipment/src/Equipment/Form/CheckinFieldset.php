<?php
namespace Equipment\Form;

use Application\Form\AbstractBaseFieldset;

class CheckinFieldset extends AbstractBaseFieldset
{
    public function __construct()
    {
        parent::__construct('checkin');

        $this->add(array(
            'name' => 'checkinDate',
            'type' => 'date',
            'attributes' => array(
                'id' => 'checkin-date',
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Checkin date')
            )
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
            )
        );
        return $inputFilter;
    }
}