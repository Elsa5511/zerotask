<?php

namespace Quiz\Form;

use Application\Form\AbstractBaseFieldset;

class QuestionFieldset extends AbstractBaseFieldset
{

    public function __construct($objectManager, $translator)
    {
        parent::__construct('question');
        $this->setTranslator($translator);

        $this->add(array(
            'name' => 'subject',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Subject'),
            ),
            'attributes' => array(
                'required' => 'true'
            ),
        ));

        $this->add(array(
            'name' => 'explanatoryText',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Explanatory text'),
            ),
            'attributes' => array(
                'required' => 'true'
            ),
        ));

        $this->add(array(
            'name' => 'questionText',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Question text'),
            ),
            'attributes' => array(
                'required' => 'true'
            ),
        ));
        
        $this->add(array(
            'name' => 'question_image',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('Image'),
            ),
        ));

        $this->add(array(
            'name' => 'remove_image',
            'type' => 'hidden',
            
        ));

        $this->add(array(
            'name' => 'resourceLink',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Resource link'),
                'description' => $translator->translate('Use relative url (e.g /ladoc/page/index/id/5) for internal links and full url (e.g http://www.google.com) for external links')
            ),
            'attributes' => array(
                'id' => 'resource-link',
            ),
        ));        

        $this->add(array(
            'name' => 'weight',
            'type' => 'number',
            'options' => array(
                'label' => $this->translate('Weighting (1-9)'),
            ),
            'attributes' => array(
                'required' => 'true'
            ),
        ));
        
        $this->add(array(
            'name' => 'type',
            'type' => 'Zend\Form\Element\Radio',
            'attributes' => array(
                'required' => 'required',
                'class' => 'question-type'
            ),
            'options' => array(
                'label' => $this->translate('Question type'),
                'value_options' => array(
                    'one' => $this->translate('Single choice'),
                    'many' => $this->translate('Multiple choice'),
                ),
            ),
        ));

        $optionFieldset = new OptionFieldset($objectManager, $this->getTranslator());        
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'options',
            'options' => array(
                'label' => $this->translate('Please add answer options'),
                'count' => 1,
                'should_create_template' => true,
                'template_placeholder' => '__index__',
                'allow_add' => true,
                'target_element' => $optionFieldset
            ),
        ));
        
        $this->add(array(
            'name' => 'add',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'add-opt-btn',
                'class' => 'btn-danger',
                'value' => $this->translate('Add an option')
            ),
            'options' => array(
                'class' => 'btn-danger',
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => $this->translate('Save question'),
                'id' => 'submit-question-btn'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'type' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => $this->getTypeValidators()
            ),
            'weight' => array(
                'required' => false,
                'validators' => $this->getWeightValidators()
            ),
            'resourceLink' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
            ),
            'question_image' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\File\MimeType',
                        'options' => array(
                            'mimeType' => array(
                                'image/jpg',
                                'image/gif',
                                'image/png',
                                'image/jpeg'
                            ),
                            'messages' => array(
                                \Zend\Validator\File\MimeType::FALSE_TYPE =>
                                $this->translate('The file must be of type ') . '(jpg, jpeg, gif, png)',
                                \Zend\Validator\File\MimeType::NOT_DETECTED =>
                                $this->translate('The file was not detected'),
                            )
                        ),
                    ),
                ),
            ),
        );
        return $inputFilter;
    }

    private function getWeightValidators()
    {
        $validation = array(
            $this->getNumericalValuesValidator(),
            $this->getBetweenValuesValidator(1, 9)
        );
        return $validation;
    }

    private function getTypeValidators()
    {
        $validation = array(
            $this->getNotEmptyValidator(),
            $this->getLengthValidator(1, 255),
        );
        return $validation;
    }

}
