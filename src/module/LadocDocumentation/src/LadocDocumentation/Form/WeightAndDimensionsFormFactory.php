<?php

namespace LadocDocumentation\Form;

use Application\Form\AbstractFormFactory;
use LadocDocumentation\Entity\CarrierDimensions;
use LadocDocumentation\Entity\CarrierDimensionsAttachment;
use LadocDocumentation\Entity\CarrierWeight;
use LadocDocumentation\Entity\CarrierWeightAttachment;
use Sysco\Aurora\Form\Form;

class WeightAndDimensionsFormFactory extends AbstractFormFactory {

    public function createLoadForm($mode) {
        $fieldset = new LoadWeightAndDimensionsFieldset($this->getTranslator(), $this->getObjectManager(), $mode);
        $this->setupFieldset($fieldset, 'LadocDocumentation\Entity\LoadWeightAndDimensions');
        return $this->setupForm($fieldset);
    }

    public function createCarrierForm() {
        $referencedFieldsets = array(
            'ownWeight' => $this->createOwnWeightFieldset(),
            'technicalWeight' => $this->createTechnicalWeightFieldset(),
            'ownDimensions' => $this->createOwnDimensionsFieldset(),
            'loadingPlanDimensions' => $this->createLoadingPlanDimensionsFieldset(),
        );

        $fieldset = new CarrierWeightAndDimensionsFieldset($this->getTranslator(), $referencedFieldsets);
        $this->setupFieldset($fieldset, 'LadocDocumentation\Entity\CarrierWeightAndDimensions');
        return $this->setupForm($fieldset);
    }

    private function createOwnWeightFieldset() {
        $ownWeightAttachmentFieldset = new MultipleAttachmentFieldset($this->getObjectManager(),
            'LadocDocumentation\Entity\CarrierWeightAttachment', new CarrierWeightAttachment());

        $ownWeightFieldset = new CarrierWeightFieldset($this->getTranslator(),
            $ownWeightAttachmentFieldset, 'own-weight');

        $this->setupReferencedFieldsets($ownWeightFieldset, 'LadocDocumentation\Entity\CarrierWeight',
            new CarrierWeight());

        return $ownWeightFieldset;
    }

    private function createTechnicalWeightFieldset() {
        $technicalWeightAttachmentFieldset = new MultipleAttachmentFieldset($this->getObjectManager(),
            'LadocDocumentation\Entity\CarrierWeightAttachment', new CarrierWeightAttachment());

        $technicalWeightFieldset = new CarrierWeightFieldset($this->getTranslator(),
            $technicalWeightAttachmentFieldset, 'technical-weight');

        $this->setupReferencedFieldsets($technicalWeightFieldset, 'LadocDocumentation\Entity\CarrierWeight',
            new CarrierWeight());

        return $technicalWeightFieldset;
    }
    private function createOwnDimensionsFieldset() {
        $ownDimensionsAttachmentFieldset = new MultipleAttachmentFieldset($this->getObjectManager(),
            'LadocDocumentation\Entity\CarrierDimensionsAttachment', new CarrierDimensionsAttachment());

        $ownDimensionsFieldset = new CarrierDimensionsFieldset($this->getTranslator(),
            $ownDimensionsAttachmentFieldset, 'own-dimensions');

        $this->setupReferencedFieldsets($ownDimensionsFieldset, 'LadocDocumentation\Entity\CarrierDimensions',
            new CarrierDimensions());

        return $ownDimensionsFieldset;
    }

    private function createLoadingPlanDimensionsFieldset() {
        $loadingPlanDimensionsAttachmentFieldset = new MultipleAttachmentFieldset($this->getObjectManager(),
            'LadocDocumentation\Entity\CarrierDimensionsAttachment', new CarrierDimensionsAttachment());

        $loadingPlanDimensionsFieldset = new CarrierDimensionsFieldset($this->getTranslator(),
            $loadingPlanDimensionsAttachmentFieldset, 'loading-plan-dimensions');

        $this->setupReferencedFieldsets($loadingPlanDimensionsFieldset, 'LadocDocumentation\Entity\CarrierDimensions',
            new CarrierDimensions());

        return $loadingPlanDimensionsFieldset;
    }

    private function setupForm($fieldset) {
        $fieldset->setObjectManager($this->getObjectManager());
        $form = new Form('weight-and-dimensions');
        $form->add($fieldset);
        return $form;
    }

    private function setupReferencedFieldsets($fieldset, $objectPath, $object) {
        $hydrator = $this->getHydratorForm($objectPath);
        $fieldset
            ->setHydrator($hydrator)
            ->setObject($object);
        $fieldset->setTranslator($this->getTranslator());
        $fieldset->setUseAsBaseFieldset(false);
    }
}