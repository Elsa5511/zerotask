<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Equipment\Entity\ControlPoint;
use Equipment\Entity\ControlPointToTemplate;
use Sysco\Aurora\Form\Fieldset;

class ControlPointResultFieldset extends Fieldset
{

    const DEFAULT_ENCODING = "UTF-8";
    const REGEX_FOR_NAMES = "/^([ \x{00C0}-\x{01FF}a-zA-Z'\-])+$/u";

    protected $objectManager;
    protected $translator;
    protected $controlPoint;

    /**
     * @var ControlPointToTemplate
     */
    protected $controlPointToTemplate;

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    public function setObjectManager($value)
    {
        $this->objectManager = $value;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function setTranslator($value)
    {
        $this->translator = $value;
    }
    
//    public function getControlPoint() {
//        return $this->controlPoint;
//    }
//
//    public function setControlPoint(\Equipment\Entity\ControlPoint $controlPoint) {
//        $this->controlPoint = $controlPoint;
//    }

    /**
     * @return ControlPointToTemplate
     */
    public function getControlPointToTemplate() {
        return $this->controlPointToTemplate;
    }

    /**
     * @param ControlPointToTemplate $controlPointToTemplate
     */
    public function setControlPointToTemplate(ControlPointToTemplate $controlPointToTemplate) {
        $this->controlPointToTemplate = $controlPointToTemplate;
    }

    /**
     * @return ControlPoint
     */
    public function getControlPoint() {
        return $this->controlPointToTemplate->getControlPoint();
    }


    public function __construct(ObjectManager $objectManager,
                                ControlPointToTemplate $controlPointToTemplate,
                                $name = 'control-point-result') {
        parent::__construct($name);
        
//        $this->setControlPoint($controlPoint);
        $this->setControlPointToTemplate($controlPointToTemplate);
        
        $targetObject = new \Equipment\Entity\ControlPointResult();
        $targetObject->setControlPoint($this->controlPoint);
        $targetObject->setControlPointToTemplate($controlPointToTemplate);

        $this->setObjectManager($objectManager);
        $this->setHydrator(
            new DoctrineHydrator($objectManager, 
                'Equipment\Entity\ControlPointResult', false))->setObject($targetObject);
        
        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'controlPointOption',
                'options' => array(
                    'object_manager' => $objectManager,
                    'target_class' => 'Equipment\Entity\ControlPointOption',
                    'empty_option' => $this->translate('Choose an option'),
                    'label' => $this->getControlPoint()->getLabel(),
                    'value_options' => $this->getControlPoint()->getControlPointOptionsAsArray(),
                ),
            )
        );
        
        $this->add(
            array(
                'name' => 'remark',
                'attributes' => array(
                    'type' => 'textarea',
                    'placeholder' => $this->translate('Remark'),
                    'rows' => 1
                ),
                'options' => array(
                    
                    
                ),
            )
        );
        
    }

    /**
     * Define InputFilterSpecifications
     *
     * @access public
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $iFilter = array(
            'controlPointOption' => array(
                'required' => false,
            ),
            'remark' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
            ),
        );
        return $iFilter;
    }

    protected function getTextFilters()
    {
        return array(
            array(
                'name' => 'StripTags'
            ),
            array(
                'name' => 'StringTrim'
            )
        );
    }
}

