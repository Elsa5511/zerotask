<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractFormFactory;
use Sysco\Aurora\Form\Form;

class BasicInformationFormFactory extends AbstractFormFactory {

    public function createLoadBasicInformationForm() {
        $fieldSet = new LoadBasicInformationFieldset($this->getObjectManager(),
            $this->getTranslator());
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\LoadBasicInformation');
        return $this->createBasicInformationForm($fieldSet);
    }

    public function createCarrierBasicInformationForm() {
        $fieldSet = new CarrierBasicInformationFieldset($this->getObjectManager(), $this->getTranslator());
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\CarrierBasicInformation');
        return $this->createBasicInformationForm($fieldSet);
    }

    private function createBasicInformationForm($fieldSet) {
        $fieldSet->setObjectManager($this->getObjectManager());
        $form = new Form('basic-information');
        $form->add($fieldSet);
        return $form;
    }

    public function createDescriptionInformationForm() {
        $fieldSet = new DescriptionInformationFieldset($this->getTranslator());
        $fieldSet->setObjectManager($this->getObjectManager());
        $form = new Form('description-information');
        $form->add($fieldSet);
        return $form;
    }
}
