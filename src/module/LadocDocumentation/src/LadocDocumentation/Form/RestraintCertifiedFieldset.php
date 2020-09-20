<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractBaseFieldset;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;

class RestraintCertifiedFieldset extends AbstractBaseFieldset {

    private $mode = 'add';
    private $type;
    private $templateType;

    public function __construct($objectManager, $translator, $mode = 'add', $type = 'load', $templateType = null) {
        parent::__construct('point');

        $this->setTranslator($translator);

        $this->mode = $mode;
        $this->type = $type;
        $this->templateType = $templateType;

        /*$this->add(array(
            'name' => 'removed_image_illustration',
            'type' => 'hidden',
            'attributes' => array(
                'class' => 'removed_image_illustration'
            )
        ));

        $this->add(array(
            'name' => 'image_file_illustration',
            'type' => 'file',
            'options' => array(
                'label' => $this->translate("Illustration image"),
                'required' => false
            )
        ));

        $this->add(array(
            'name' => 'removed_image',
            'type' => 'hidden',
            'attributes' => array(
                'class' => 'removed_image'
            )
        ));

        $this->add(array(
            'name' => 'image_file',
            'type' => 'file',
            'options' => array(
                'required' => $this->mode == 'add' ? true : false,
                'label' => $this->translate("Load restraint sheet image"),
            ),
            'attributes' => array(
                'id' => 'main-image'
            )
        ));*/

        switch($this->templateType) {
            case EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD:
            case EquipmentTaxonomyTemplateTypes::NAVAL_CRAFT:
                $this->add(array(
                    'name' => 'removed_image_information',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));
        
                $this->add(array(
                    'name' => 'image_information_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => false,
                        'label' => $this->translate("Certificate"),
                    )
                ));
        
                $this->add(array(
                    'name' => 'removed_calculation_information',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));
        
                $this->add(array(
                    'name' => 'calculation_information_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => false,
                        'label' => $this->translate("Calculation"),
                    )
                ));
                break;
            case EquipmentTaxonomyTemplateTypes::AIRCRAFT:
                $this->add(array(
                    'name' => 'removed_attla',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));

                $this->add(array(
                    'name' => 'attla_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => $this->mode == 'add' ? true : false,
                        'label' => $this->translate("Attla"),
                    ),
                    'attributes' => array(
                        'id' => 'main-image'
                    )
                ));
        
                $this->add(array(
                    'name' => 'removed_control_list',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));
        
                $this->add(array(
                    'name' => 'control_list_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => false,
                        'label' => $this->translate("Control list")
                    )
                ));
                break;
            case EquipmentTaxonomyTemplateTypes::RAILWAY:
                $this->add(array(
                    'name' => 'removed_railway_certificate',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));

                $this->add(array(
                    'name' => 'railway_certificate_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => false,
                        'label' => $this->translate("Certificate"),
                    )
                ));

                $this->add(array(
                    'name' => 'removed_railway_tunell_profile',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));

                $this->add(array(
                    'name' => 'railway_tunell_profile_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => $this->mode == 'add' ? true : false,
                        'label' => $this->translate("Tunell Profile")
                    ),
                    'attributes' => array(
                        'id' => 'main-image'
                    )
                ));

                $this->add(array(
                    'name' => 'removed_railway_calculation',
                    'type' => 'hidden',
                    'attributes' => array(
                        'class' => 'removed_image'
                    )
                ));

                $this->add(array(
                    'name' => 'railway_calculation_file',
                    'type' => 'file',
                    'options' => array(
                        'required' => false,
                        'label' => $this->translate("Calculation")
                    )
                ));
                break;
            default:
                break;
        }

        /*$this->add(array(
            'name' => 'otherLoads',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("The same certification is valid for")
            ),
            'attributes' => array(
                'maxlength' => 255
            )
        ));*/

        if ($type == 'load') {
            $this->add(array(
                'name' => 'carrierDocumentation',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'LadocDocumentation\Entity\LadocDocumentation',
                    'empty_option' => $this->translate('Choose a Carrier'),
                    'label' => $this->translate('Carrier'),
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'customFindBy',
                        'params' => array(
                            'criteria' => array('type' => 'carrier', 'finished' => true, 'template_type' => $templateType)
                        )
                    )
                ),
                'attributes' => array(
                    'required' => true
                )
            ));
        }
        else {
            $this->add(array(
                'name' => 'loadDocumentation',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'LadocDocumentation\Entity\LadocDocumentation',
                    'empty_option' => $this->translate('Choose a Load'),
                    'label' => $this->translate('Load'),
                    'is_method' => true,
                    'find_method' => array(
                        'name' => 'customFindBy',
                        'params' => array(
                            'criteria' => array('type' => 'load', 'finished' => true)
                        )
                    )
                ),
                'attributes' => array(
                    'required' => true
                )
            ));
        }

        /*$this->add(array(
            'name' => 'approvedFormsOfTransportation',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'label' => $translator->translate('Approved for'),
                'object_manager' => $objectManager,
                'target_class' => 'LadocDocumentation\Entity\FormOfTransportation',
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'required' => true
            )
        ));

        $this->add(array(
            'name' => 'createdBy',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Elaborated by")
            ),
            'attributes' => array(
                'maxlength' => 50,
                'required' => true
            )
        ));

        $this->add(array(
            'name' => 'approvedBy',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate("Approved by")
            ),
            'attributes' => array(
                'maxlength' => 50,
                'required' => true
            )
        ));

        $this->add(array(
            'name' => 'approvedDate',
            'type' => 'date',
            'attributes' => array(
                'id' => 'approved-date',
            ),
            'options' => array(
                'label' => $this->translate('Approved date')
            )
        ));

        $this->add(array(
            'name' => 'prerequisites',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate("Important prerequisites")
            ),
            'attributes' => array(
                'rows' => 8,
                'class' => 'span8 richtext-field'
            )
        ));

        $attachmentFieldset = new RestraintAttachmentFieldset($objectManager, $mode,
            'LadocDocumentation\Entity\LadocRestraintCertifiedAttachment',
            new \LadocDocumentation\Entity\LadocRestraintCertifiedAttachment());
        $attachmentFieldset->setTranslator($translator);
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'ladocRestraintCertifiedAttachments',
            'options' => array(
                'label' => $this->translate('Add attachments'),
                'count' => 0,
                'should_create_template' => true, //this is necessary for load the files in the request variable
                'allow_add' => true,
                'target_element' => $attachmentFieldset
            ),
        ));

        $this->add(array(
            'name' => 'add',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'add-point-btn',
                'class' => 'btn-danger',
                'value' => $this->translate('Add attachment')
            ),
            'options' => array(
                'class' => 'btn-danger',
            )
        ));*/

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'multiple' => 'multiple',
                'value' => $this->translate('Save changes')
            ),
        ));
    }

    public function getInputFilterSpecification() {
        $inputFilter = array(
            /*'otherLoads' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 255)
                )
            ),
            'createdBy' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'approvedBy' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(1, 50)
                )
            ),
            'approvedDate' => array(
                'required' => false,
                'validators' => array(
                    $this->getDateValidator()
                ),
            ),
            'prerequisites' => array(
                'required' => false,
                //'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(0, 4000)
                )
            ),
            'image_file' => array(
                'required' => $this->mode == 'add' ? true : false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidator(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'image_information_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),*/
            'calculation_information_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'attla_file' => array(
                'required' => $this->mode == 'add' && $this->templateType == EquipmentTaxonomyTemplateTypes::AIRCRAFT ? true : false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'control_list_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'railway_calculation_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'railway_certificate_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
            'railway_tunell_profile_file' => array(
                'required' => $this->mode == 'add' && $this->templateType == EquipmentTaxonomyTemplateTypes::RAILWAY ? true : false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidatorForPdf(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
        );

        /*$inputFilter['image_file_illustration'] = array(
            'required' => false,
            'type' => 'Zend\InputFilter\FileInput',
            'validators' => array(
                $this->getUploadFileValidator(),
                $this->getMimeTypeValidator(),
                $this->getMaxSizeFileValidator('20MB'),
            ),
        );*/

        if ($this->type == 'load')
            $inputFilter['carrierDocumentation'] = array('required' => true);
        else
            $inputFilter['loadDocumentation'] = array('required' => true);

        return $inputFilter;
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

    private function getMimeTypeValidator() {
        return array(
            'name' => '\Zend\Validator\File\MimeType',
            'options' => array(
                'mimeType' => array(
                    'image/jpg',
                    'image/gif',
                    'image/png',
                    'image/jpeg',
                ),
                'messages' => array(
                    \Zend\Validator\File\MimeType::NOT_DETECTED =>
                        $this->getTranslator()->translate('The file was not detected'),
                    \Zend\Validator\File\MimeType::FALSE_TYPE =>
                        $this->getTranslator()->translate('The file type must be images (jpg, jpeg, gif, png)'),
                )
            )
        );
    }

    private function getMimeTypeValidatorForPdf() {
        return array(
            'name' => '\Zend\Validator\File\MimeType',
            'options' => array(
                'mimeType' => array(
                    'application/pdf',
                ),
                'messages' => array(
                    \Zend\Validator\File\MimeType::NOT_DETECTED =>
                        $this->getTranslator()->translate('The file was not detected'),
                    \Zend\Validator\File\MimeType::FALSE_TYPE =>
                        $this->getTranslator()->translate('The file type must be pdf'),
                )
            )
        );
    }
}