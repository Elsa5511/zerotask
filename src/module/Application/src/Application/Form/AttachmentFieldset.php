<?php

namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;

class AttachmentFieldset extends AbstractBaseFieldset {

    protected $mode;

    public function __construct(ObjectManager $objectManager, $mode) {

        parent::__construct('attachment_form');

        $this->mode = $mode;

        $this->add(array(
            'name' => 'title',
            'attributes' => array('required' => true),
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Title'),
            ),
        ));

        $this->add(array(
            'name' => 'author',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Author'),
            ),
        ));

        $this->add(
                array(
                    'name' => 'attachmentTaxonomy',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'empty_option' => $this->translate('Choose Type'),
                        'label' => $this->translate('Type'),
                        'object_manager' => $objectManager,
                        'target_class' => 'Application\Entity\AttachmentTaxonomy',
                        'property' => 'type',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array(),
                                'orderBy' => array('type' => 'ASC')
                            )
                        )
                    )
        ));

        $this->addChildFields();

        $this->add(array(
            'name' => 'version',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Version'),
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'textarea',
            'options' => array(
                'label' => $this->translate('Description'),
            ),
        ));
    }

    protected function addChildFields() {
        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'id' => 'attachment_id',
                'required' => true
            ),
            'type' => 'File',
            'options' => array(
                'label' => $this->translate('File'),
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'attachmentTaxonomy' => array(
                'required' => false,
            ),
            'title' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getLengthValidator(3),
                    $this->getNotEmptyValidator(),
                ),
            ),
            'filename' => array(
                'required' => $this->mode == 'add' ? true : false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getUploadFileValidator(),
                    $this->getMimeTypeValidator(),
                    $this->getMaxSizeFileValidator('10MB'),
                ),
            ),
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

    private function getMimeTypeValidator() {
        return array(
            'name' => '\Zend\Validator\File\MimeType',
            'options' => array(
                'mimeType' => array(
                    'image/jpg',
                    'image/gif',
                    'image/png',
                    'image/jpeg',
                    'video/mp4', //mp4
                    'video/quicktime', //mov
                    'video/x-msvideo', //avi
                    'video/x-ms-wmv', //wmv
                    'video/x-ms-asf ', //wmv
                    'application/msword',
                    'application/application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.ms-office', // Fileinfo returns this for ppt files
                    'application/ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/pdf',
                    'application/zip', 'application/octet-stream'),
                'messages' => array(
                    \Zend\Validator\File\MimeType::NOT_DETECTED =>
                    $this->getTranslator()->translate('The file was not detected'),
                    \Zend\Validator\File\MimeType::FALSE_TYPE =>
                    $this->getTranslator()->translate('The file type must be images (jpg, jpeg, gif, png), documents (pdf, doc, docx, ppt, pptx, xls, xlsx), videos (mov, mpeg4, avi, wmv)'),
                )
            )
        );
    }

}
