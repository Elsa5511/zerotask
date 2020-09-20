<?php

namespace LadocDocumentation\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class MultipleAttachmentFieldset extends AbstractBaseFieldset {
    protected $mode;

    public function __construct(ObjectManager $objectManager, $entityPath, $entity, $required = false) {

        parent::__construct('attachment');

        $this->required = $required;

        $this->setHydrator(new DoctrineHydrator($objectManager, $entityPath))
            ->setObject(new $entity);

        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',

        ));

        $this->add(array(
            'name' => 'removed_image',
            'attributes' => array(
                'class' => 'removed_image',
                'value' => 0
            ),
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'required' => $required,
            ),
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Title'),
            ),
        ));

        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'id' => 'attachment_id'
            ),
            'type' => 'file',
            'options' => array(
                'label' => $this->translate('File'),
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'title' => array(
                'required' => $this->required,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                ),
            ),
            'filename' => array(
                'required' => $this->required,
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
