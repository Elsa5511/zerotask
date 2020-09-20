<?php

namespace Documentation\Form;

use Application\Form\AbstractFormFactory;

/**
 * Form factory class
 * Use its methods to create forms
 */
class FormFactory extends AbstractFormFactory
{


    /**
     * Creates a Location form
     * Returns an instance of Form
     */
    public function createPageForm()
    {
        $pageFieldset = $this->getPageFieldset();
        $form = $this->getNewForm('page_form');
        $form->add($pageFieldset);
        return $form;
    }

    /**
     * Get Page fieldset
     * @return \Documentation\Form\PageFieldset
     */
    private function getPageFieldset()
    {
        $pageFieldset = new PageFieldset($this->getObjectManager());
        
        $this->setupFieldset($pageFieldset, 'Documentation\Entity\Page');

        return $pageFieldset;
    }

    public function createCalculatorInfoForm()
    {
        $fieldset = new CalculatorInfoFieldset($this->getObjectManager());
        $this->setupFieldset($fieldset, 'Documentation\Entity\CalculatorInfo');

        $form = $this->getNewForm('calculator_info');
        $form->add($fieldset);
        return $form;
    }

    public function createCalculatorAttachmentForm($mode = 'add')
    {
        $fieldset = new CalculatorAttachmentFieldset($this->getObjectManager(), $mode);
        $this->setupFieldset($fieldset, 'Documentation\Entity\CalculatorAttachment');

        $form = $this->getNewForm('calculator_attachment');
        $form->add($fieldset);
        return $form;
    }

}