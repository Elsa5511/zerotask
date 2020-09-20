<?php
namespace Application\Form;

class LanguageFieldset extends AbstractBaseFieldset {

    public function __construct($translator) {
        
        parent::__construct('language');

        $this->setTranslator($translator);

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'attributes' => array('required' => true),
            'options' => array(
                'label' => $this->translate('Name'),
            )
        ));

        $this->add(array(
            'name' => 'isocode',
            'type' => 'text',
            'attributes' => array('required' => true),
            'options' => array(
                'label' => $this->translate('ISO code'),
            ),
        ));

        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    'active' => $this->translate('Active'),
                    'inactive' => $this->translate('Inactive')
                )
            ),
            'options' => array(
                'label' => $this->translate('Status'),
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
    public function getInputFilterSpecification() {
        return array(
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getOnlyLettersValidator(),
                ),
            ),
            'isocode' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getOnlyLettersValidator(),
                    $this->getLengthValidator(2, 2),                    
                ),
            )
        );
    }
}