<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Sysco\Aurora\Form\Fieldset;

class EquipmentSubinstanceFieldset extends Fieldset
{

    const DEFAULT_ENCODING = "UTF-8";
    const REGEX_FOR_NAMES = "/^([ \x{00C0}-\x{01FF}a-zA-Z'\-])+$/u";

    protected $objectManager;
    protected $translator;

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

    public function __construct(ObjectManager $objectManager, 
            $availableEquipmentInstances, $name = 'equipment_subinstance')
    {
        parent::__construct($name);

        $this->setObjectManager($objectManager);
        $this->setHydrator(
                new DoctrineHydrator($objectManager, 'Equipment\Entity\EquipmentInstance', false))->setObject(new \Equipment\Entity\EquipmentInstance());
        $this->add(
                array(
                    'type' => 'hidden',
                    'name' => 'equipmentInstanceId',
                    'attributes' => array('multiple' => 'multiple'),
                    
                )
        );
        $this->add(
                array(
                    'type' => 'Select',
                    'name' => 'childId',
                    'attributes' => array('multiple' => 'multiple'),
                    'options' => array(
                        'value_options' => $availableEquipmentInstances,
                        'label'=>$this->translate('Subinstances'),
                    ),
                    
                )
        );

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
    public function getInputFilterSpecification()
    {
        $iFilter = array(
            'childId' => array(
                'required' => false,
            ),
            'equipmentInstanceId' => array(
                'required' => true,
            ),
        );
        return $iFilter;
    }

}

