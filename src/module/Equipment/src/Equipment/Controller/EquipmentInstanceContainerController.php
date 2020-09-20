<?php

namespace Equipment\Controller;


use Equipment\Entity\EquipmentInstanceContainer;
use Equipment\Service\EquipmentInstanceContainerService;
use Zend\Form\Form;

class EquipmentInstanceContainerController extends EquipmentInstanceController {

    /**
     * @return string
     */
    protected function getControllerName() {
        return 'equipment-instance-container';
    }

    /**
     * @return EquipmentInstanceContainer
     */
    protected function createNewEquipmentInstance() {
        return new EquipmentInstanceContainer();
    }

    /**
     * @return EquipmentInstanceContainerService
     */
    protected function getEquipmentInstanceService() {
        return $this->getService('Equipment\Service\EquipmentInstanceContainerService');
    }

    /**
     * @param EquipmentInstanceContainer $equipmentInstance
     * @return Form
     */
    protected function getEquipmentInstanceForm($equipmentInstance) {
        $formFactory = $this->getFormFactory('Equipment');
        $form = $formFactory->createEquipmentInstanceContainerForm();
        $this->addRestrictionsToEquipmentInstanceForm($form);
        $form->bind($equipmentInstance);
        return $form;
    }
}