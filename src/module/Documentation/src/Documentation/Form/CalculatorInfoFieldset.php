<?php

namespace Documentation\Form;

use Documentation\Entity\CalculatorInfo;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Application\Form\AbstractBaseFieldset;

class CalculatorInfoFieldset extends AbstractBaseFieldset
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct("CalculatorInfo");

        $this->setObjectManager($objectManager);
        $this->setHydrator(
            new DoctrineHydrator($objectManager, 'Documentation\Entity\CalculatorInfo', false))
            ->setObject(new CalculatorInfo());

        $this->add(
            array(
                'name' => 'description',
                'type' => 'textarea',
                'attributes' => array(
                    'rows' => '5',
                    'class' => 'richtext-field richtext-modal',
                    'data-height' => 400
                ),
                'options' => array(
                    'label' => $this->translate('Text')
                )
            ));
    }
}