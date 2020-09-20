<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractBaseFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class RestraintNonCertifiedFieldset extends AbstractBaseFieldset {
    private $type;

    public function __construct($objectManager, $translator, $type, $templateType = null) {
        parent::__construct('point');

        $this->type = $type;
        $this->setObjectManager($objectManager);

        $this->setTranslator($translator);

        if($type == 'load') {
            $this->add($this->getCarrierDocumentationField($templateType));
        } else {
            $this->add($this->getLoadDocumentationField());
        }

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'multiple' => 'multiple',
                'value' => $this->translate('Save changes')
            ),
        ));
    }

    private function getCarrierDocumentationField($templateType) {
        return array(
            'name' => 'carrierDocumentation',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $this->getObjectManager(),
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
        );
    }

    private function getLoadDocumentationField() {
        return array(
            'name' => 'loadDocumentation',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $this->getObjectManager(),
                'target_class' => 'LadocDocumentation\Entity\LadocDocumentation',
                'empty_option' => $this->translate('Choose a Load'),
                'label' => $this->translate('Load'),
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('type' => 'load', 'finished' => true)
                    )
                )
            ),
            'attributes' => array(
                'required' => true
            )
        );
    }

    public function getInputFilterSpecification()
    {
        if($this->type == 'load') {
            $inputFilter = array(
                'carrierDocumentation' => array(
                    'required' => true
                )
            );
        } else {
            $inputFilter = array(
                'loadDocumentation' => array(
                    'required' => true
                )
            );
        }
        return $inputFilter;
    }

}