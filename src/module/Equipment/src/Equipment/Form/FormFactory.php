<?php
namespace Equipment\Form;

use Equipment\Hydrator\CustomDoctrineObject as DoctrineHydrator;
use Equipment\Form\AbstractFormFactory;

/**
 * Form factory class
 * Use its methods to create forms
 */
class FormFactory extends AbstractFormFactory
{
    /**
     * Creates an equipment instance form
     * Returns an instance of Form
     * 
     */    
    public function createEquipmentForm($categoryValues, $equipmentId, $fieldsToShow, $application = '') {
        $equipmentFieldset = $this->getEquipmentFieldset($categoryValues, $equipmentId, $fieldsToShow, $application);
        $this->setupFieldset($equipmentFieldset, 'Equipment\Entity\Equipment');

        $form = $this->getNewForm('equipment-form');
        $form->add($equipmentFieldset);

        return $form;
    }


    private function getEquipmentFieldset($categoryValues, $equipmentId, $fieldsToShow, $application = ''){
        $equipmentFieldset = new EquipmentFieldset($this->getObjectManager(),
            $categoryValues, $equipmentId, $fieldsToShow, $application);
        
        return $equipmentFieldset;
    }
    
    public function createEquipmentInstanceForm()
    {
        $fieldset = new EquipmentInstanceBaseFieldset($this->getObjectManager(), $this->getTranslator());
        $this->setupFieldset($fieldset, 'Equipment\Entity\EquipmentInstance');
        $form = $this->getNewForm('equipment-instance');
        $form->add($fieldset);    
        return $form;
    }

    public function createEquipmentInstanceContainerForm()
    {
        $fieldset = new EquipmentInstanceContainerFieldset($this->getObjectManager(), $this->getTranslator());
        $this->setupFieldset($fieldset, 'Equipment\Entity\EquipmentInstanceContainer');
        $form = $this->getNewForm('equipment-instance-container');
        $form->add($fieldset);
        return $form;
    }
    
    public function createEquipmentInstanceEditManyForm($removeRegNumberField = true)
    {
        $form = $this->getNewForm('equipment-instance');

        // The fieldset will hydrate an object entity
        $hydrator = $this->getHydratorForm('Equipment\Entity\EquipmentInstance');

        // edit the user fieldset, and set it as the base fieldset
        $fieldset = new EquipmentInstanceEditManyFieldset($this->getObjectManager(), $this->translator);
        $fieldset->remove('serialNumber');
        if($removeRegNumberField)
            $fieldset->remove('regNumber');
        $fieldset->setHydrator($hydrator);
        $fieldset->setUseAsBaseFieldset(true);
        $form->add($fieldset);
        return $form;
    }

    public function createEquipmentSubinstanceForm($availableEquipmentInstances)
    {
        $fieldset = new EquipmentSubinstanceFieldset($this->getObjectManager(), 
                $availableEquipmentInstances);
        $this->setupFieldset($fieldset, 
                'Equipment\Entity\EquipmentInstance');
        $form = $this->getNewForm('equipment_subinstance');
        $form->add($fieldset);    
        return $form;
    }
    
    public function createEquipmentTaxonomyForm($equipmentTaxonomyId, $application = "")
    {
        $fieldset = new EquipmentTaxonomyFieldset($this->getObjectManager(), 
                                                    $equipmentTaxonomyId,
                                                    $application);
        $this->setupFieldset($fieldset, 'Equipment\Entity\EquipmentTaxonomy');
        $form = $this->getNewForm('equipment_taxonomy_form');
        $form->add($fieldset);
        return $form;
    }
    
    /**
     * Creates Visual Control form
     * Returns an instance of Form
     * 
     */
    public function createVisualControlForm($post)
    {
        $visualControlFieldset = new VisualControlFieldset($this->getObjectManager());        
        $this->setupFieldset($visualControlFieldset, 
                'Equipment\Entity\VisualControl');

        $form = new VisualControlForm();
        $form->addingHiddenElements($post);
        $form->add($visualControlFieldset);
        return $form;
    }
    
    /**
     * Creates Periodic Control form
     * Returns an instance of Form
     * 
     */
    public function createPeriodicControlForm($controlPointToTemplateArray, $userId, $post)
    {
        $periodicControlFieldset = new PeriodicControlFieldset($this->getObjectManager(),
            $userId, $this->getTranslator());
        $this->setupFieldset($periodicControlFieldset, 
                'Equipment\Entity\PeriodicControl');

        $form = new PeriodicControlForm();
        $form->add($periodicControlFieldset);

        $this->addingControlPoints($form, $controlPointToTemplateArray);
        $this->addAttachmentsToPeriodicControlForm($form);

        $form->addingHiddenElements($post);
        $form->addAttachmentButtons();
        $form->addSubmitButton();

        return $form;
    }
    
    public function createAdvancedSearchForm($objectManager, $application) {
        $advancedSearchFieldset = new SearchAdvancedFieldset($objectManager, $this->getTranslator(), $application);
        $advancedSearchFieldset->setUseAsBaseFieldset(true);
        $form = new \Sysco\Aurora\Form\Form();
        $form->add($advancedSearchFieldset);
        return $form;
    }

    public function createAdvancedSearchForInstancesForm($objectManager, $application) {
        $advancedSearchFieldset = new SearchAdvancedForInstancesFieldset($objectManager, $this->getTranslator(), $application);
        $advancedSearchFieldset->setUseAsBaseFieldset(true);
        $form = new \Sysco\Aurora\Form\Form();
        $form->add($advancedSearchFieldset);
        return $form;
    }

    public function createAdvancedSearchForInstanceControlForm($objectManager, $application) {
        $advancedSearchFieldset = new SearchAdvancedForInstanceControlFieldset($objectManager, $this->getTranslator(), $application);
        $advancedSearchFieldset->setUseAsBaseFieldset(true);
        $form = new \Sysco\Aurora\Form\Form();
        $form->add($advancedSearchFieldset);
        return $form;
    }
    
    /**
     * 
     * @param mixed $form
     * @param array $controlPointCollection
     */
    private function addingControlPoints($form, $controlPointToTemplateArray) {
        $counterControlPoints = 1;
        foreach ($controlPointToTemplateArray as $controlPointToTemplate) {
            $controlPointResultFieldset = new ControlPointResultFieldset(
                    $this->getObjectManager(), 
                    $controlPointToTemplate,
                    'control-point-result-' . $counterControlPoints);
            
            $hydrator = $this->getHydratorForm('Equipment\Entity\ControlPointResult');
            $controlPointResultFieldset->setHydrator($hydrator);
            $controlPointResultFieldset->setTranslator($this->getTranslator());    

            $form->add($controlPointResultFieldset);            
            $counterControlPoints++;
        }
        
    }

    private function addAttachmentsToPeriodicControlForm(PeriodicControlForm $form)
    {
        $attachmentFieldset = new \Application\Form\AttachmentWithLinkFieldset($this->getObjectManager(), 'add');
        $hydrator = $this->getHydratorForm('Equipment\Entity\PeriodicControlAttachment');
        $attachmentFieldset->setHydrator($hydrator);
        $attachmentFieldset->setObject(new \Equipment\Entity\PeriodicControlAttachment());
        $attachmentFieldset->setTranslator($this->getTranslator());
        $element = array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'periodicControlAttachments',
            'options' => array(
                'label' => $this->getTranslator()->translate('Add attachments'),
                'count' => 0,
                'should_create_template' => true, //this is necessary for load the files in the request variable
                'allow_add' => true,
                'target_element' => $attachmentFieldset
            ),
        );

        $form->add($element);  
    }
    
    /**
     * Creates Checkout form
     *
     * @param array $post
     * @return CheckoutForm instance of Form
     */
    public function createCheckoutForm($post)
    {
        $checkoutFieldset = new CheckoutFieldset($this->getObjectManager());
        $this->setupFieldset($checkoutFieldset, 'Equipment\Entity\Checkout');
        $form = new CheckoutForm();
        $form->addingHiddenElements($post);
        $form->add($checkoutFieldset);
        return $form;
    }

    /**
     * Creates Checkin form
     * 
     * @return mixed instance of Form
     */
    public function createCheckinForm()
    {
        $checkinFieldset = new CheckinFieldset();
        $this->setupFieldset($checkinFieldset, 'Equipment\Entity\Checkin');
        $form = $this->getNewForm('checkin');
        $form->add($checkinFieldset);
        return $form;
    }

    protected function getHydratorForm($entityName)
    {
        return new DoctrineHydrator($this->getObjectManager(), $entityName, false);
    }
}