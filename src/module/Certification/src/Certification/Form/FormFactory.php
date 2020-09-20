<?php

namespace Certification\Form;

use Application\Form\AbstractFormFactory;

/**
 * Form factory class
 * Use its methods to create forms
 */
class FormFactory extends AbstractFormFactory
{

    /**
     * Creates a Certification form
     * Returns an instance of Form
     */
    public function createCertificationForm()
    {
        $certificationFieldset = $this->getCertificationFieldset();
        $form = $this->getNewForm('certification');
        $form->add($certificationFieldset);        
        return $form;
    }

    /**
     * Get certification fieldset
     * @return \Certification\Form\CertificationFieldset
     */
    private function getCertificationFieldset()
    {
        $certificationFieldset = new CertificationFieldset($this->getObjectManager());
        $this->setupFieldset($certificationFieldset, 'Certification\Entity\Certification');
        return $certificationFieldset;
    }

    /**
     * Creates a Certification search form
     * Returns an instance of Form
     * 
     */
    public function createCertificationSearchForm()
    {
        $searchFieldset = new CertificationSearchFieldset($this->getObjectManager(), $this->getTranslator());
        $form = $this->getNewForm('certification-search');
        $form->add($searchFieldset);        
        return $form;
    }
}