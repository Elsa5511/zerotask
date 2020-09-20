<?php

namespace Application\Form;

use Sysco\Aurora\Form\Form;
use Application\Form\SectionFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class SectionForm extends Form
{

    public function __construct(ObjectManager $objectManager,$entityPath)
    {

        parent::__construct('section_form');

        $sectionFieldset = new SectionFieldset($objectManager,$entityPath);
        $sectionFieldset->setUseAsBaseFieldset(true);
        $this->add($sectionFieldset);
    }

}