<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Equipment\Form\EquipmentInstanceBaseFieldset;

class EquipmentInstanceEditManyFieldset extends EquipmentInstanceBaseFieldset
{

    public function __construct(ObjectManager $objectManager, $translator, $name = 'equipment-instance')
    {
        parent::__construct($objectManager, $translator, $name);
        $this->setTranslator($translator);

        $this->add(
                array(
                    'name' => 'listEquipmentInstances',
                    'type' => 'hidden',
        ));
        $this->add(
                array(
                    'name' => 'checkUpdate',
                    'type' => 'checkbox',
                    'attributes'=>array(
                        'id'=>'auth_update'
                    ),
                    'options' => array(
                        'label' => $this->translate('Update visual control ?'),
                        'value' => 1
                    )
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
        $filter = parent::getInputFilterSpecification();
        $filter['serialNumber'] = array(
            'required' => false,
        );
        return $filter;
    }

}

