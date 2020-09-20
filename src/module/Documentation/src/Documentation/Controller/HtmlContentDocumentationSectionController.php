<?php

namespace Documentation\Controller;

use Documentation\Controller\HtmlContentController;

class HtmlContentDocumentationSectionController extends HtmlContentController
{

    public function getHtmlContentDivId($inlineSectionId)
    {
        return 'documentationContent' . $inlineSectionId;
    }

    public function getHtmlContentService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\HtmlContentDocumentationSectionService');
    }

    public function getCriteriaToFindOwner($documentationSectionId)
    {
        return array('documentationSection' => $documentationSectionId);
    }

    public function getNewHtmlContentEntity($documentationSectionId)
    {
        $documentationSection = $this->getDocumentationSectionService()->getSection($documentationSectionId);
        if (empty($documentationSection)) {
            $namespace = 'error';
            $message = $this->getTranslator('The Section does not exist');
            $this->sendFlashMessage($message, $namespace, true);
            return null;
        }
        $HtmlContentDocumentationSectionEntity = new \Documentation\Entity\HtmlContentDocumentationSection();
        $HtmlContentDocumentationSectionEntity->setDateAdd('NOW');
        $HtmlContentDocumentationSectionEntity->setDocumentationSection($documentationSection);
        return $HtmlContentDocumentationSectionEntity;
    }

    private function getDocumentationSectionService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\DocumentationSectionService');
    }

}