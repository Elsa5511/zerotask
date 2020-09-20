<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractFormFactory;

class PointFormFactory extends AbstractFormFactory {

    private $mode = "add";

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function createLoadLashingPointForm() {
        $loadLashingPointFieldset = $this->getLoadLashingPointFieldset();
        $loadLashingPointForm = $this->getNewForm('point_form');
        $loadLashingPointForm->add($loadLashingPointFieldset);

        return $loadLashingPointForm;
    }

    private function getLoadLashingPointFieldset() {
        $loadLashingPointFieldset = new LoadLashingPointFieldset($this->getObjectManager(), $this->getTranslator(), $this->mode);
        $this->setupFieldset($loadLashingPointFieldset, 'LadocDocumentation\Entity\LoadLashingPoint');
        return $loadLashingPointFieldset;
    }

    public function createCarrierLashingPointForm() {
        $carrierLashingPointFieldset = $this->getCarrierLashingPointFieldset();
        $carrierLashingPointForm = $this->getNewForm('point_form');
        $carrierLashingPointForm->add($carrierLashingPointFieldset);

        return $carrierLashingPointForm;
    }

    private function getCarrierLashingPointFieldset() {
        $carrierLashingPointFieldset = new CarrierLashingPointFieldset($this->getObjectManager(), $this->getTranslator(), $this->mode);
        $this->setupFieldset($carrierLashingPointFieldset, 'LadocDocumentation\Entity\CarrierLashingPoint');
        return $carrierLashingPointFieldset;
    }

    public function createLoadLiftingPointForm() {
        $loadLiftingPointFieldset = $this->getLoadLiftingPointFieldset();
        $loadLiftingPointForm = $this->getNewForm('point_form');
        $loadLiftingPointForm->add($loadLiftingPointFieldset);

        return $loadLiftingPointForm;
    }

    private function getLoadLiftingPointFieldset() {
        $loadLiftingPointFieldset = new LoadLiftingPointFieldset($this->getObjectManager(), $this->getTranslator(), $this->mode);
        $this->setupFieldset($loadLiftingPointFieldset, 'LadocDocumentation\Entity\LoadLiftingPoint');
        return $loadLiftingPointFieldset;
    }

    public function createLashingEquipmentForm() {
        $fieldSet = new CarrierLashingEquipmentFieldset($this->getObjectManager(), $this->getTranslator(), $this->mode);
        $this->setupFieldset($fieldSet, 'LadocDocumentation\Entity\CarrierLashingEquipment');
        
        $form = $this->getNewForm('point_form');
        $form->add($fieldSet);
        return $form;
    }
}