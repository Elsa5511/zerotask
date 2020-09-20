<?php

namespace Documentation\Controller;

use Application\Controller\SectionController;
use Documentation\Entity\PageSection;

class PageSectionController extends SectionController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'page-section',
        );
    }
    public function getSectionService()
    {
        return $this->getService('Documentation\Service\PageSectionService');
    }

    protected function getSectionEntityWithOwner($ownerId)
    {
        $page = $this->getOwnerEntityService()->getPage($ownerId);
        $section = new PageSection();
        $section->setPage($page);
        return $section;
    }

    protected function getOwnerEntityService()
    {
        return $this->getService('Documentation\Service\PageService');
    }

    protected function getOwnerEntityPath()
    {
        return 'Documentation\Entity\PageSection';
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

}
