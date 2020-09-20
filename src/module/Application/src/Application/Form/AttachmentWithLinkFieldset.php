<?php

namespace Application\Form;

class AttachmentWithLinkFieldset extends AttachmentFieldset {

    public function __construct($objectManager, $mode) {
        parent::__construct($objectManager, $mode);
    }

    protected function addChildFields() {
        $this->add(array(
            'name' => 'removed_attachment',
            'attributes' => array('class' => 'removed_attachment'),
            'type' => 'hidden'
        ));

        $this->add(array(
            'name' => 'filename',
            'attributes' => array(
                'id' => 'attachment_id',
                'required' => false
            ),
            'type' => 'File',
            'options' => array(
                'label' => $this->translate('File'),
            ),
        ));

        $this->add(array(
            'name' => 'link',
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Link'),
            ),
        ));
    }

    public function getInputFilterSpecification() {
        $inputFilterSpesification = parent::getInputFilterSpecification();
        $inputFilterSpesification['filename']['required'] = false;
        $inputFilterSpesification['link'] = array(
            'required' => false,
            'validators' => array(array(
                'name' => 'Uri',
                'options' => array(
                    'allowRelative' => false,
                    'messages' => array(
                        \Zend\Validator\Uri::INVALID => $this->getTranslator()->translate('Invalid link. Please use full location ("http://...")'),
                        \Zend\Validator\Uri::NOT_URI => $this->getTranslator()->translate('Invalid link. Please use full location ("http://...")'),
                    )
                )
            )));
        return $inputFilterSpesification;
    }
}
