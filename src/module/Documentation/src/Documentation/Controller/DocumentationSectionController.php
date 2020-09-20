<?php

namespace Documentation\Controller;

use Application\Controller\SectionController;
use Documentation\Entity\DocumentationSection;

class DocumentationSectionController extends SectionController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'documentation-section',
        );
    }
    public function getSectionService()
    {
        return $this->getService('Documentation\Service\DocumentationSectionService');
    }

    protected function getSectionEntityWithOwner($ownerId)
    {
        $equipment = $this->getOwnerEntityService()->getEquipment($ownerId);
        $section = new DocumentationSection();
        $section->setEquipment($equipment);
        return $section;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\DocumentationSection';
    }

    protected function getOwnerController()
    {
        return 'documentation';
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
