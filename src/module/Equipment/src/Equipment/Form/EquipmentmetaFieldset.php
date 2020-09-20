<?php
namespace Equipment\Form;

use Equipment\Entity\Equipmentmeta;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Form\AbstractBaseFieldset;

class EquipmentmetaFieldset extends AbstractBaseFieldset
{
    const REGEX_NSN = "/^.{9}$|^.{13}$/";

    protected $objectManager;
    protected $equipmentId;

    public function __construct(ObjectManager $objectManager,$equipmentId = 0)
    {
        $this->objectManager = $objectManager;
        $this->equipmentId = $equipmentId;

        parent::__construct('equipmentmeta');

        $this->setHydrator(new DoctrineHydrator($objectManager, 'Equipment\Entity\Equipmentmeta'))
                ->setObject(new Equipmentmeta());

          $this->add(array(
            'name' => 'nsn',
            'type' => 'Text',
            'attributes' => array(
                'id' => "nsn",
                'maxlength' => 13,
                'required' =>false
            ),
            'options' => array(
                'label' => 'NATO #',
            ),
        ));
        $this->add(array(
            'name' => 'sap',
            'type' => 'Text',
            'attributes' => array(
                'maxlength' => 8
            ),
            'options' => array(
                'label' => 'SAP #',
            ),
        ));


        $this->add(array(
            'name' => 'vendor_part',
            'type' => 'Text',
            'attributes' => array(
                'placeholder' => $this->translate('Vendor part'),
            ),
            'options' => array(
                'label' => $this->translate('Vendor part'),
            )
        ));
    }

    public function getInputFilterSpecification()
    {
        $inputFilter = array(
            'sap' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => $this->sapValidator($this->equipmentId),
            ),
            'nsn' => array(
                'required' => false,
                'filters' => $this->getTextFilters(),
                'validators' => $this->nsnValidator($this->equipmentId),
            ),
        );
        return $inputFilter;
    }
    
    private function sapValidator($equipmentId)
    {
        if ($equipmentId === 0) {
            $excludeArray = array();            
        } else {
            $excludeArray = array(
                'field' => 'equipment',
                'value' => $this->equipmentId,
            );
        }
        $validator = $this->sapNsnValidator($excludeArray, "SAP #");

        array_push($validator, array(
            'name' => 'Digits',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\Digits::NOT_DIGITS =>
                        $this->getTranslator()->translate("The input must contain only digits")
                )
            ),
        ));
        array_push($validator, $this->getLengthValidator(8, 8));
        return $validator;
    }
    
    private function nsnValidator($equipmentId)
    {
        if ($equipmentId === 0) {
            $excludeValue = '1';            
        } else {
            $excludeValue = $this->equipmentId;
        }
        $excludeArray = array(
            'field' => 'equipment',
            'value' => $excludeValue,
        );
        $validators = $this->sapNsnValidator($excludeArray, "NATO #");
        array_push($validators, array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => self::REGEX_NSN,
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH =>
                        $this->getTranslator()->translate('NATO no. must be 9 or 13 characters.')
                )
            )
        ));
//        array_push($validators, $this->getLengthValidator(9, 16));
        return $validators;
    }
    
    private function sapNsnValidator($excludeArray, $label)
    {
        $format = $this->getTranslator()->translate("%s is already registered");
        $noObjectMessage = sprintf($format, $label);

        return array(
            $this->getNotEmptyValidator(),
            array(
                'name' => 'Application\Validator\NoObjectExists',
                'options' => array(
                    'object_repository' => $this->objectManager->getRepository('Equipment\Entity\Equipmentmeta'),
                    'fields' => 'value',
                    'exclude' => $excludeArray,
                    'messages' => array(
                        \DoctrineModule\Validator\NoObjectExists::ERROR_OBJECT_FOUND
                        => $noObjectMessage
                    )
                ),
            ),
            $this->getNotEmptyValidator()
        );
    }

}