<?php


namespace Equipment\Controller;

use Equipment\Entity\VedosMechanicalEquipment;
use Equipment\Service\VedosMechanicalEquipmentService;

class VedosMechanicalEquipmentController extends EquipmentController {

    protected function getEquipmentForm($equipment, $equipmentId = 0) {
        $formFactory = $this->getFormFactory('Equipment');
        $optionValues = $this->getEquipmentTaxonomyService()->getAvailableEquipmentTaxonomy(0);
        $equipmentForm = $formFactory->createVedosMechanicalEquipmentForm($optionValues, $equipmentId);
        $equipmentForm->bind($equipment);
        return $equipmentForm;
    }

    protected function prepareEquipmentForSave($equipment) {
        $wll = $equipment->getWll();

        if (empty($wll)) {
            $equipment->setWll(null);
        }

        $length = $equipment->getLength();
        if (empty($length)) {
            $equipment->setLength(null);
        }
    }

    /**
     * @return VedosMechanicalEquipment
     */
    protected function getNewEquipment() {
        return new VedosMechanicalEquipment();
    }

    protected function getEntityResource() {
        return 'Equipment\Entity\VedosMechanicalEquipment';
    }

}