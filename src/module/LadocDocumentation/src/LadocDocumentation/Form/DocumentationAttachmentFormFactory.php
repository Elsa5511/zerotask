<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractFormFactory;
use Sysco\Aurora\Form\Form;

class DocumentationAttachmentFormFactory extends AbstractFormFactory {

    public function createDocumentationAttachmentForm($mode = 'add') {
        $fieldSet = new LadocDocumentationAttachmentFieldset($this->getObjectManager(), $this->getTranslator(), $mode);
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\LadocDocumentationAttachment');
        $fieldSet->setObjectManager($this->getObjectManager());
        $form = new Form('documentation-attachment');
        $form->add($fieldSet);
        return $form;
    }
}