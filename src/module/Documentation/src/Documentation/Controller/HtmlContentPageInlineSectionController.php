<?php

namespace Documentation\Controller;

use Documentation\Controller\HtmlContentController;

class HtmlContentPageInlineSectionController extends HtmlContentController
{

    public function getHtmlContentDivId($inlineSectionId)
    {
        return 'inlineContent' . $inlineSectionId;
    }

    public function getHtmlContentService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\HtmlContentPageInlineSectionService');
    }

    public function getCriteriaToFindOwner($inlineSectionId)
    {
        return array('pageInlineSection' => $inlineSectionId);
    }

    public function getNewHtmlContentEntity($inlineSectionId)
    {
        $inlineSection = $this->getPageInlineSectionService()->getSection($inlineSectionId);
        if (empty($inlineSection)) {
            $namespace = 'error';
            $message = $this->getTranslator('The section does not exist');
            $this->sendFlashMessage($message, $namespace, true);
            return false;
        }
        $HtmlContentInlineSectionEntity = new \Documentation\Entity\HtmlContentPageInlineSection();
        $HtmlContentInlineSectionEntity->setDateAdd('NOW');
        $HtmlContentInlineSectionEntity->setPageInlineSection($inlineSection);
        return $HtmlContentInlineSectionEntity;
    }

    private function getPageInlineSectionService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\PageInlineSectionService');
    }

}