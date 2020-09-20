<?php

namespace Documentation\Controller;

use Documentation\Controller\HtmlContentController;

class HtmlContentPageSectionController extends HtmlContentController
{

    public function getHtmlContentDivId($inlineSectionId)
    {
        return 'documentationContent' . $inlineSectionId;
    }

    public function getHtmlContentService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\HtmlContentPageSectionService');
    }

    public function getCriteriaToFindOwner($documentationSectionId)
    {
        return array('pageSection' => $documentationSectionId);
    }

    public function getNewHtmlContentEntity($documentationSectionId)
    {
        $documentationSection = $this->getPageSectionService()->getSection($documentationSectionId);
        if (empty($documentationSection)) {
            $namespace = 'error';
            $message = $this->getTranslator('The section does not exist');
            $this->sendFlashMessage($message, $namespace, true);
            return null;
        }
        $HtmlContentDocumentationSectionEntity = new \Documentation\Entity\HtmlContentPageSection();
        $HtmlContentDocumentationSectionEntity->setDateAdd('NOW');
        $HtmlContentDocumentationSectionEntity->setPageSection($documentationSection);
        return $HtmlContentDocumentationSectionEntity;
    }

    private function getPageSectionService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\PageSectionService');
    }

}