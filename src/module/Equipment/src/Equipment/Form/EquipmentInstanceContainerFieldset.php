<?php


namespace Equipment\Form;


use Doctrine\Common\Persistence\ObjectManager;

class EquipmentInstanceContainerFieldset extends EquipmentInstanceBaseFieldset {

    public function __construct(ObjectManager $objectManager, $translator, $name = 'equipment-instance') {
        parent::__construct($objectManager, $translator, $name);
    }

    protected function addChildFields() {
        $this->add(array(
            'name' => 'isIsolated',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('Isolated')
            ),
        ));

        $this->add(array(
            'name' => 'hasDryair',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('Dryair')
            ),
        ));

        $this->add(array(
            'name' => 'hasVolt220',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('220 volt')
            ),
        ));

        $this->add(array(
            'name' => 'hasVolt400',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('400 volt')
            ),
        ));

        $this->add(array(
            'name' => 'hasCommunicationRacks',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('Communication racks')
            ),
        ));

        $this->add(array(
            'name' => 'hasOtherDecor',
            'type' => 'select',
            'attributes' => array(
                'options' => array(
                    '1' => $this->translate('Yes'),
                    '0' => $this->translate('No')
                )
            ),
            'options' => array(
                'label' => $this->translate('Other decor')
            ),
        ));
    }
}