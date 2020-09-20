<?php

namespace Equipment\Form;

use Doctrine\Common\Persistence\ObjectManager;
use Application\Form\AbstractBaseFieldset;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;

class EquipmentTaxonomyFieldset extends AbstractBaseFieldset {

    protected $objectManager;
    protected $parentCategories;

    public function __construct(ObjectManager $objectManager, $equipmentTaxonomyId, $application = "") {
        $this->setObjectManager($objectManager);
        parent::__construct('equipment_taxonomy');

        $this
                ->add(array(
                    'name' => 'parent',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'attributes' => array(
                        'id' => 'category_id',
                    ),
                    'options' => $this->getParentOptions($equipmentTaxonomyId),
                ))
                ->add(array(
                    'name' => 'name',
                    'type' => 'text',
                    'attributes' => array(
                        'required' => true
                    ),
                    'options' => array(
                        'label' => $this->translate('Name'),
                    )
                ))
                ->add(array(
                    'name' => 'description',
                    'type' => 'textarea',
                    'attributes' => array(
                    ),
                    'options' => array(
                        'label' => $this->translate('Description'),
                    ),
                ))
                ->add(array(
                    'name' => 'status',
                    'type' => 'select',
                    'attributes' => array(
                        'options' => array(
                            'active' => 'Active',
                            'inactive' => 'Inactive'
                        )
                    ),
                    'options' => array(
                        'label' => $this->translate('Status'),
                    )
                    ));
        if($application != "ladoc") {
            $this
                    ->add(array(
                        'name' => 'controlTemplate',
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'attributes' => array(
                            'id' => 'control_template',
                        ),
                        'options' => array(
                            'label' => $this->translate('Control Template'),
                            'object_manager' => $this->getObjectManager(),
                            'target_class' => 'Equipment\Entity\ControlTemplate',
                            'empty_option' => $this->translate('None'),
                            'property' => 'name',
                        ),
                    ))
                    ->add(array(
                        'name' => 'competenceAreaTaxonomy',
                        'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                        'attributes' => array(
                            'id' => 'competence_area_taxonomy'
                        ),
                        'options' => array(
                            'label' => $this->translate('Competence area'),
                            'object_manager' => $this->getObjectManager(),
                            'target_class' => 'Equipment\Entity\CompetenceAreaTaxonomy',
                            'empty_option' => $this->translate('None'),
                            'property' => 'name'
                        )
                        ));
        } else { // Only for LADOC
            $this->add(
                array(
                    'name' => 'templateType',
                    'type' => 'select',
                    'attributes' => array(
                        'options' => array(
                            EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD => $this->translate('Country road'),
                            EquipmentTaxonomyTemplateTypes::NAVAL_CRAFT => $this->translate('Sea'),
                            EquipmentTaxonomyTemplateTypes::AIRCRAFT => $this->translate('Air'),
                            EquipmentTaxonomyTemplateTypes::RAILWAY => $this->translate('Railway')
                        )
                    ),
                    'options' => array(
                        'label' => $this->translate('Armed forces'),
                        'empty_option' => "-"
                    ),
                )
            );
        }

        $this
                ->add(array(
                    'name' => 'featured_image_file',
                    'type' => 'file',
                    'options' => array(
                        'label' => $this->translate('Featured image'),
                    )
        ));
    }

    private function getParentOptions($equipmentTaxonomyId) {
        return array(
            'empty_option' => $this->translate('None'),
            'label' => $this->translate('Parent Category'),
            'object_manager' => $this->getObjectManager(),
            'target_class' => 'Equipment\Entity\EquipmentTaxonomy',
            'find_method' => array(
                'name' => 'fetchParentCategories',
                'params' => array(
                    'equipmentTaxonomyId' => $equipmentTaxonomyId
                )
            )
        );
    }

    public function getInputFilterSpecification() {
        return array(
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => array(
                    $this->getNotEmptyValidator(),
                    $this->getLengthValidator(3)
                ),
            ),
            'parent' => array(
                'required' => false,
            ),
            'controlTemplate' => array(
                'required' => false,
            ),
            'competenceAreaTaxonomy' => array(
                'required' => false,
            ),
            'featured_image_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    $this->getImageFileValidator()
                ),
            ),
            'templateType' => array(
                'required' => false,
            ),
        );
    }

}
