<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractFormFactory;
use Sysco\Aurora\Form\Form;

class RestraintDocumentationFormFactory extends AbstractFormFactory {

    public function createRestraintCertifiedForm($mode = 'add', $type, $templateType) {
        $fieldSet = new RestraintCertifiedFieldset($this->getObjectManager(), $this->getTranslator(), $mode, $type, $templateType);
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\LadocRestraintCertified');
        $form = $this->getNewForm('point_form');
        $form->add($fieldSet);
        return $form;
    }

    public function createRestraintNonCertifiedForm($type, $templateType) {
        $fieldSet = new RestraintNonCertifiedFieldset($this->getObjectManager(), $this->getTranslator(), $type, $templateType);
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\LadocRestraintNonCertified');
        $form = $this->getNewForm('point_form');
        $form->add($fieldSet);
        return $form;
    }

    public function createRestraintCertifiedDocumentForm($mode = 'add') {
        $fieldSet = new RestraintCertifiedDocumentFieldset($this->getObjectManager(), $this->getTranslator(), $mode);
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\LadocRestraintCertifiedDocument');
        $fieldSet->setObjectManager($this->getObjectManager());
        $form = new Form('restraint-document');
        $form->add($fieldSet);
        return $form;
    }
}