<?php
namespace BestPractice\Form;

use Application\Form\AbstractFormFactory;

/**
 * Form factory class
 * Use its methods to create forms
 */
class FormFactory extends AbstractFormFactory
{
    
    /**
     * Creates a BestPractice form
     * Returns an instance of Form
     */
    public function createBestPracticeForm($bestPracticeId)
    {
        $bestPracticeFieldset = $this->getBestPracticeFieldset($bestPracticeId);
        $form = new BestPracticeForm();
        $form->add($bestPracticeFieldset);        
        return $form;
    }

    /**
     * Get best practice fieldset
     * 
     * @param type $bestPracticeId
     * @return \BestPractice\Form\BestPracticeFieldset
     */
    private function getBestPracticeFieldset($bestPracticeId)
    {
        $bestPracticeFieldset = new BestPracticeFieldset($bestPracticeId);
        $this->setupFieldset($bestPracticeFieldset, 'BestPractice\Entity\BestPractice');
        return $bestPracticeFieldset;
    }
    
    /**
     * Creates a Attachment form
     * Returns an instance of Form
     *
     * @param string $entityPath 
     * @param string $mode
     * @return object Form
     */
    public function createAttachmentForm($entityPath,$mode)
    {
        $form = $this->getNewForm('attachment-form');
        $attachmentFieldset = new AttachmentFieldset($this->getObjectManager(), $mode);
        $this->setupFieldset($attachmentFieldset, $entityPath);
        $form->add($attachmentFieldset);

        return $form;
    }

}