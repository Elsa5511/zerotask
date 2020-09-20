<?php

namespace Documentation\Controller;

use Documentation\Controller\HtmlContentController;

class HtmlContentInlineSectionController extends HtmlContentController
{

    public function getHtmlContentDivId($inlineSectionId)
    {
        return 'inlineContent' . $inlineSectionId;
    }

    public function getHtmlContentService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\HtmlContentInlineSectionService');
    }

    public function getCriteriaToFindOwner($inlineSectionId)
    {
        return array('inlineSection' => $inlineSectionId);
    }

    public function getNewHtmlContentEntity($inlineSectionId)
    {
        $inlineSection = $this->getInlineSectionService()->getSection($inlineSectionId);
        if (empty($inlineSection)) {
            $namespace = 'error';
            $message = $this->getTranslator('The section does not exist');
            $this->sendFlashMessage($message, $namespace, true);
            return false;
        }
        $HtmlContentInlineSectionEntity = new \Documentation\Entity\HtmlContentInlineSection();
        $HtmlContentInlineSectionEntity->setDateAdd('NOW');
        $HtmlContentInlineSectionEntity->setInlineSection($inlineSection);
        return $HtmlContentInlineSectionEntity;
    }

    private function getInlineSectionService()
    {

        return $this->getServiceLocator()
                        ->get('Documentation\Service\InlineSectionService');
    }

}