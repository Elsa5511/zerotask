<?php

namespace Training\Controller;

use Application\Controller\SectionController;
use Training\Entity\TrainingSection;

class TrainingSectionController extends SectionController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'training-section',
        );
    }
    public function getSectionService()
    {
        return $this->getService('Training\Service\TrainingSectionService');
    }

    protected function getSectionEntityWithOwner($ownerId)
    {
        $equipment = $this->getOwnerEntityService()->getEquipment($ownerId);
        $section = new TrainingSection();
        $section->setEquipment($equipment);
        return $section;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Training\Entity\TrainingSection';
    }

    protected function getOwnerController()
    {
        return 'training';
    }

    protected function getOwnerFieldName()
    {
        return 'equipment';
    }
     protected function actionAfterDelete()
    {
        return 'index';
    }

}
