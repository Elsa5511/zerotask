<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Sysco\Aurora\Form\Fieldset;
use Equipment\Entity\EquipmentTaxonomy;

class SearchAdvancedFieldset extends Fieldset {

    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $translator, $application) {
        $this->objectManager = $objectManager;

        parent::__construct('equipment');

        $this->setHydrator(new DoctrineHydrator($objectManager, 'Equipment\Entity\Equipment'))
                ->setObject(new EquipmentTaxonomy());


        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'category',
                    'attributes' => array(
                        'id' => 'category',
                        'multiple' => 'multiple',
                        'data-placeholder' => $translator->translate('Choose a category'),
                    ),
                    'options' => array(
                        'object_manager' => $objectManager,
                        'target_class' => 'Equipment\Entity\EquipmentTaxonomy',
                        'property' => 'name',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('status' => 'active', 
                                                    'type' => 'category',
                                                    'application' => $application),
                                'orderBy' => array('name' => 'ASC'),
                            ),
                        ),
                    ),
                )
        );
        $this->add(
                array(
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'name' => 'supplier',
                    'attributes' => array(
                        'id' => 'supplier',
                        'multiple' => 'multiple',
                        'data-placeholder' => $translator->translate('Choose a supplier'),
                    ),
                    'options' => array(
                        'object_manager' => $this->objectManager,
                        'target_class' => 'Application\Entity\Organization',
                        'property' => 'name',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'findBy',
                            'params' => array(
                                'criteria' => array('status' => 'active', 'type' => 'vendor'),
                                'orderBy' => array('name' => 'ASC'),
                            ),
                        ),
                    ),
                )
        );

        $nsnOptions = array(
            'name' => 'nsn',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'nsn',
            ),
        );
        if($application !== 'ladoc')
            $nsnOptions['attributes']['maxlength'] = 16;
        $this->add($nsnOptions);

        $sapOptions = array(
            'name' => 'sap',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'sap',
                //'data-mask' => "99999999",
            ),
        );
        if($application !== 'ladoc')
            $sapOptions['attributes']['maxlength'] = 8;
        $this->add($sapOptions);

        $this->add(array(
            'name' => 'button',
            'type' => 'button',
            'attributes' => array(
                'label' => 'Search',
                'class' => 'btn',
                'value' => $this->translate('Search'),
                'id' => 'btn-add-application'
            ),
        ));
    }

    public function getInputFilterSpecification() {
        return array();
    }

}
