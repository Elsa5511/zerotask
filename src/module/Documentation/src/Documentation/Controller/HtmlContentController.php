<?php

namespace Documentation\Controller;

use Application\Controller\AbstractBaseController;

abstract class HtmlContentController extends AbstractBaseController
{

    /**
     * This method return the id of the div that contains the html code
     */
    abstract function getHtmlContentDivId($sectionId);

    /**
     * this method get the service 
     */
    abstract function getHtmlContentService();

    /**
     * this method return an array that is a filter to find a section
     */
    abstract function getCriteriaToFindOwner($sectionId);

    /**
     * this method return a new htmlcontent entity and if the owner does not exist show and error
     */
    abstract function getNewHtmlContentEntity($sectionId);

    public function saveAction()
    {
        $sectionId = $this->params()->fromRoute('id', 0);
        $request = $this->getRequest();
        $httpReferer = $request->getServer('HTTP_REFERER');

        if ($request->isPost()) {
            $post = $request->getPost();
            return $this->storePostData($post, $httpReferer, $sectionId);
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToHttpReferer($httpReferer);
        }
    }

    private function getHtmlContentEntity($sectionId)
    {
        $criteria = $this->getCriteriaToFindOwner($sectionId);
        $htmlContentEntity = $this->getHtmlContentService()->getHtmlContent($criteria);

        if (empty($htmlContentEntity)) {
            $htmlContentEntity = $this->getNewHtmlContentEntity($sectionId);
        }
        return $htmlContentEntity;
    }

    private function storePostData($post, $httpReferer, $sectionId)
    {
        $htmlContentEntity = $this->getHtmlContentEntity($sectionId);

        if (empty($htmlContentEntity)) {
            return $this->redirectToHttpReferer($httpReferer);
        }

        $htmlContentDivId = $this->getHtmlContentDivId($sectionId);
        $message = $this->getHtmlContentService()->saveHtmlContent($htmlContentEntity, $post->{$htmlContentDivId});
        $this->sendFlashMessage($message['message'], $message['namespace'], true);

        return $this->redirectToHttpReferer($httpReferer);
    }

    public function redirectToHttpReferer($httpReferer)
    {
        return $this->redirect()->toUrl($httpReferer);
    }

}