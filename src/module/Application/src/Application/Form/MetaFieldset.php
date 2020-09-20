<?php

namespace Application\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Sysco\Aurora\Form\Fieldset;

class MetaFieldset extends Fieldset
{
    public function __construct(ObjectManager $objectManager, $metaEntityName)
    {
        parent::__construct('meta');

        $this->setHydrator(new DoctrineHydrator($objectManager, $metaEntityName))
                ->setObject(new $metaEntityName());

        $this->add(array(
            'type' => 'Hidden',
            'name' => 'metaId'
        ));
        $this->add(array(
            'name' => 'key',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'value',
            'type' => 'Text',
        ));
    }
}