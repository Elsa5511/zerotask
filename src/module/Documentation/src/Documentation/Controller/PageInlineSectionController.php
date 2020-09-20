<?php

namespace Documentation\Controller;

use Application\Controller\SectionController;
use Documentation\Entity\PageInlineSection;

class PageInlineSectionController extends SectionController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'page-inline-section',
        );
    }
    public function getSectionService()
    {
        return $this->getService('Documentation\Service\PageInlineSectionService');
    }

    protected function getSectionEntityWithOwner($ownerId)
    {
        
        $pageSection = $this->getOwnerEntityService()->getSection($ownerId);

        $pageInlineSection = new PageInlineSection();
        $pageInlineSection->setPageSection($pageSection);
        return $pageInlineSection;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\PageSectionService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\pageInlineSection';
    }

    protected function getOwnerController()
    {
        return 'page';
    }

    protected function getOwnerFieldName()
    {
        return 'page';
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
