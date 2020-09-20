<?php

namespace Documentation\Form;

use Documentation\Entity\Page;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Form\AbstractBaseFieldset;

class PageFieldset extends AbstractBaseFieldset
{

    public function __construct(ObjectManager $objectManager, $name = 'page')
    {
        parent::__construct($name);

        $this->setObjectManager($objectManager);
        $this->setHydrator(
                new DoctrineHydrator($objectManager, 'Documentation\Entity\Page', false))->setObject(
                new Page());

        $this->add(
                array(
                    'name' => 'category',
                    'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                    'options' => array(
                        'empty_option' => $this->translate('None'),
                        'label' => $this->translate('Category'),
                        'object_manager' => $this->getObjectManager(),
                        'target_class' => 'Equipment\Entity\EquipmentTaxonomy',
                        'property' => 'name',
                        'is_method' => true,
                        'find_method' => array(
                            'name' => 'getActive',
                            'params' => array()
                        )
                    )
        ));

        $this->add(
                array(
                    'name' => 'name',
                    'type' => 'text',
                    'attributes' => array(
                        'required' => 'required'
                    ),
                    'options' => array(
                        'label' => $this->translate('Name')
                    )
        ));

        $this->add(
                array(
                    'name' => 'featured_image_file',
                    'type' => 'file',
                    'options' => array(
                        'label' => $this->translate('Featured Image')
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
        $inputFilter = array(
            'category' => array(
                'required' => false
            ),
            'name' => array(
                'required' => true,
                'filters' => $this->getTextFilters(),
                'validators' => $this->getNameValidator()
            ),
            'featured_image_file' => array(
                'required' => false,
                'type' => 'Zend\InputFilter\FileInput',
                'validators' => array(
                    array(
                        'name' => '\Zend\Validator\File\MimeType',
                        'options' => array(
                            'mimeType' => array(
                                'image/jpg',
                                'image/gif',
                                'image/png',
                                'image/jpeg'
                            ),
                            'messages' => array(
                                \Zend\Validator\File\MimeType::FALSE_TYPE =>
                                $this->translate('The file must be of type ') . '(jpg, jpeg, gif, png)',
                                \Zend\Validator\File\MimeType::NOT_DETECTED =>
                                $this->translate('The file was not detected'),
                            )
                        ),
                    ),
                ),
            ),
        );
        return $inputFilter;
    }

    private function getNameValidator()
    {
        $validation = array(
            $this->getNotEmptyValidator(),
            $this->getLengthValidator(),
            $this->getOnlyLettersNumbersValidator(),
        );
        return $validation;
    }

}
