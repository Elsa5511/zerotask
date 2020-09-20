<?php

namespace Documentation\Controller;

use Application\Controller\SectionController;
use Documentation\Entity\InlineSection;

class InlineSectionController extends SectionController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'inline-section',
        );
    }
    public function getSectionService()
    {
        return $this->getService('Documentation\Service\InlineSectionService');
    }

    protected function getSectionEntityWithOwner($ownerId)
    {
        
        $documentation = $this->getOwnerEntityService()->getSection($ownerId);

        $section = new InlineSection();
        $section->setDocumentation($documentation);
        return $section;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\DocumentationSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\InlineSection';
    }

    protected function getOwnerController()
    {
        return 'documentation';
    }

    protected function getOwnerFieldName()
    {
        return 'documentation';
    }
     protected function actionAfterDelete()
    {
        return 'index';
    }
    
    public function getSectionForm($entity, $mode = 'add')
    {

        $entityPath = $this->getOwnerEntityPath();
        $formFactory = $this->getServiceLocator()->get('\Application\Form\FormFactory');
        $parentOptions = array();
        $form = $formFactory->createSectionForm($entityPath, $parentOptions, $mode);
        $form->bind($entity);

        return $form;
    }

}
