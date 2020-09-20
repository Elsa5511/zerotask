<?php
namespace BestPractice\Form;

use Application\Form\AbstractBaseFieldset;

class BestPracticeFieldset extends AbstractBaseFieldset 
{

    public function __construct($bestPracticeId) {
        
        parent::__construct('best-practice');

        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'required' => true,
            ),
            'options' => array(
                'label' => $this->translate('Title'),
            )
        ));
        
        $this->add(array(
            'name' => 'subtitle',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Subtitle'),
            )
        ));
        
        $this->add(array(
            'name' => 'featuredImage',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('Featured image'),
            ),
            'attributes' => array(
                'id' => 'featuredImage'
            )
        ));
        
        $this->add(array(
            'name' => 'revisionNumber',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Revision number'),
            ),
            'attributes' => array(
                'required' => true,
            )
        ));
        
        $this->add(array(
            'name' => 'slide-one',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('Slide 1'),
            ),
            'attributes' => array(
                'required' => true,
                'id' => 'slide-one'
            )
        ));
        
        $this->add(array(
            'name' => 'slide-two',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('Slide 2'),
            ),
            'attributes' => array(
                'id' => 'slide-two'
            )
        ));

        if($bestPracticeId > 0) {
            $this->add(array(
                'type' => 'Zend\Form\Element\Checkbox',
                'name' => 'new-revision',
                'options' => array(
                    'label' => $this->translate('Generate a new revision'),
                    'use_hidden_element' => true,
                    'checked_value' => '1',
                    'unchecked_value' => '0'
                ), 
                'attributes' => array(
                    'data-toggle'=> 'collapse',
                    'data-target'=> '#revision-comment',
                    'id' => 'new-revision'
                )
            ));
            
            $this->add(
                array(
                    'name' => 'revision-comment',
                    'type' => 'textarea',
                    'options' => array(
                        'label' => '',
                    ),
                    'attributes' => array(
                        'class'=> 'collapse',
                        'id'=> 'revision-comment',
                        'placeholder' => $this->translate('Comments about new revision')
                    )
            ));
        }
        
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
    public function getInputFilterSpecification() {
        return array(
            'title' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getOnlyLettersNumbersValidator()
                ),
            ),
            'subtitle' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getOnlyLettersNumbersValidator()
                ),
            ),
            'revisionNumber' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                ),
            ),
            'featuredImage' => $this->inputFilterForImages(),
            'slide-one' => $this->inputFilterForImages(true),
            'slide-two' => $this->inputFilterForImages()
            
        );
    }
    
    private function inputFilterForImages($isRequired = false)
    {
        return array(
            'required' => $isRequired,
            'type' => 'Zend\InputFilter\FileInput',
            'validators' => array(
                $this->getUploadFileValidator(),
                $this->getImageFileValidator()
            )
        );
    }
    
    private function getUploadFileValidator() {
        return array(
            'name' => '\Zend\Validator\File\UploadFile',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\File\UploadFile::NO_FILE =>
                    $this->getTranslator()->translate('The file was not detected'),
                )
            ),
            'break_chain_on_failure' => true
        );
    }
}