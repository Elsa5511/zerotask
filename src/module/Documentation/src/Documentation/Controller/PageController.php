<?php

namespace Documentation\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

class PageController extends AbstractBaseController
{

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $actionName = $controller->params()->fromRoute('action');
            $applicationName = $controller->params()->fromRoute('application');
            if (in_array($actionName, array('index', 'simple-search'))) {
                $pageId = $controller->params()->fromRoute('id', 0);
                $page = $controller->getPage($pageId);
                if($page && $page->isSearchEnabled()) {
                    $searchForms = $controller->forward()->dispatch('Controller\Page',
                        array('action' => 'page-simple-search', 'application' => $applicationName, 'id' => $pageId, 'word' => @$_GET['keyWord']));
                    $controller->layout()->addChild($searchForms, 'searchForms');
                }
            }
        }, -100); // execute after executing action logic

        return $this;
    }

    public function pageSimpleSearchAction() {
        $pageId = $this->params()->fromRoute('id');
        $page = $this->getPage($pageId);

        $view = new ViewModel(array(
            "currentPage" => $page,
            'word' => $this->params()->fromRoute('word', '')
        ));

        $view->setTemplate('partial/page-simple-search.phtml');

        return $view;
    }

    public function simpleSearchAction()
    {
        $word = trim($_GET['keyWord']);
        $pageId = $this->params()->fromRoute('id', 0);
        $page = $this->getPage($pageId);
        if (empty($page)) {
            $this->sendTranslatedFlashMessage($this->translate("This page doesn't exist"), 'error');
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForCategoryPage($page, "category-page-search");

        if (empty($word) || strlen($word) < 3) {
            $result = null;
            $this->sendTranslatedFlashMessage($this->translate("You must use 3 characters at least"), 'error');
        } else {
            $result = $this->getSearchResults($page, $word);
        }

        return new ViewModel(
            array(
                'currentPage' => $page,
                'result' => $result
            )
        );
    }

    private function setFormattedResult(array &$result, \Documentation\Entity\PageSection $pageSection) {
        if(array_key_exists($pageSection->getSectionId(), $result))    return;

        $content = $pageSection->getHtmlContent() == null ? '' : strip_tags($pageSection->getHtmlContent()->getHtmlContent());

        if(strlen($content) > 200)
            $content = substr($content, 0, 200) . '...';

        $result[$pageSection->getSectionId()] = array(
            'title' => $pageSection->getLabel(),
            'content' => $content
        );
    }

    private function getSearchResults(\Documentation\Entity\Page $page, $word)
    {
        $result = array();

        $pageSectionsFound = $this->getPageSectionService()->searchByWords($word, 'page', $page->getPageId());
        if($pageSectionsFound) {
            foreach($pageSectionsFound as $pageSectionFound) {
                $this->setFormattedResult($result, $pageSectionFound);
            }
        }

        $sections = $this->getPageSectionService()->getInlineSections($page->getPageId(), 'page'); //this function is used also to get sections (not only inline sections)
        $sectionIds = array();
        if($sections)
            foreach($sections as $section)
                $sectionIds[] = $section->getSectionId();
        $pageInlineSectionsFound = $this->getPageInlineSectionService()->searchByWords($word, 'pageSection', $sectionIds);
        if($pageInlineSectionsFound) {
            foreach($pageInlineSectionsFound as $pageInlineSectionFound) {
                $pageSection = $pageInlineSectionFound->getPageSection();
                $this->setFormattedResult($result, $pageSection);
            }
        }

        $htmlContentPageSectionsFound = $this->getHtmlContentPageSectionService()->searchByWords($word, 'pageSection', $sectionIds);
        if($htmlContentPageSectionsFound) {
            foreach($htmlContentPageSectionsFound as $htmlContentPageSectionFound) {
                $pageSection = $htmlContentPageSectionFound->getPageSection();
                $this->setFormattedResult($result, $pageSection);
            }
        }

        $inlineSections = $this->getPageInlineSectionService()->getInlineSectionsByArray('pageSection', $sections);
        $inlineSectionIds = array();
        if($inlineSections)
            foreach($inlineSections as $inlineSection)
                $inlineSectionIds[] = $inlineSection->getSectionId();
        $htmlContentPageInlineSectionsFound = $this->getHtmlContentPageInlineSectionService()->searchByWords($word, 'pageInlineSection', $inlineSectionIds);
        if($htmlContentPageInlineSectionsFound) {
            foreach($htmlContentPageInlineSectionsFound as $htmlContentPageInlineSectionFound) {
                $pageInlineSection = $htmlContentPageInlineSectionFound->getPageInlineSection();
                $pageSection = $pageInlineSection->getPageSection();
                $this->setFormattedResult($result, $pageSection);
            }
        }

        return $result;
    }

    public function indexAction()
    {
        $pageId = $this->params()->fromRoute('id', 0);
        $page = $this->getPage($pageId);
        if (empty($page)) {
           $this->sendTranslatedFlashMessage($this->translate("This page doesn't exist"), 'error');
           return $this->redirectToReferer();
        }
        $this->setBreadcrumbForCategoryPage($page);

        $sectionId = $this->params()->fromRoute('sectionId', 0);
        $currentSection = array();
        
        if ($sectionId) {
            $currentSection = $this->getPageSectionService()->getSection($sectionId);         
        }
       
        if (empty($currentSection)) {       
            $currentSection = $this->getPageSectionService()->getFirstContentSection($pageId,'page');
             
            $sectionId = $currentSection ? $currentSection->getSectionId() : null;
        }
  
        $sections = $this->getPageSectionService()->getParentSections($pageId,'page');
        
        $inlineSections = $this->getPageInlineSectionService()->getInlineSections($sectionId,'pageSection');

        return new ViewModel(
                array(
            'title' => $page->getName(),
            'pageId' => $pageId,
            'sections' => $sections,
            'currentSection' => $currentSection,
            'inlineSections' => $inlineSections
                )
        );
    }

    public function deleteAction()
    {
        $pageId = $this->params()->fromRoute('id', 0);

        if ($pageId > 0) {
            $flashMessengerArray = $this->getPageService()->deleteById($pageId);
            $this->sendFlashMessage($flashMessengerArray['message'], $flashMessengerArray['namespace'], true);
        } else {
            $this->sendFlashMessage($this->translate('Incorrect Page id format'), 'error');
        }
        $request = $this->getRequest();
        $httpReferer = $request->getServer('HTTP_REFERER');
        return $this->redirect()->toUrl($httpReferer);
    }

    public function addPageAction()
    {

        $this->layout('layout/iframe');
        $parentTaxonomyId = (int) $this->params()->fromRoute('category', 0);
        $page = $this->getPageService()->getNewPage($parentTaxonomyId);

        $pageForm = $this->getPageForm($page);

        return $this->managePostSave($pageForm, $page);
    }

    public function editPageAction()
    {
        $this->layout('layout/iframe');
        $pageId = $this->params()->fromRoute('id', false);
        if ($pageId) {
            $page = $this->getPage($pageId);
            $pageForm = $this->getPageForm($page);
            return $this->managePostSave($pageForm, $page);
        } else {
            return $this->displayGenericErrorMessage();
        }
    }

    public function getPageForm($page)
    {

        $formFactory = $this->getFormFactory("Documentation");
        $form = $formFactory->createPageForm();
        $form->bind($page);

        return $form;
    }

    /**
     * 
     * @param Form $pageForm
     * @param \Documentation\Entity\Page $page
     * @return type
     */
    private function managePostSave($pageForm, $page)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            return $this->storePostData($post, $pageForm);
        } else {

            return $this->displayForm($pageForm, $page);
        }
    }

    /**
     * 
     * @param array $post
     * @param Form $pageForm
     * @return \Zend\View\Model\ViewModel
     */
    private function storePostData($post, $pageForm)
    {
        $pageService = $this->getPageService();
        $pageForm->setData($post);
        $page = $pageForm->getObject();

        if ($pageForm->isValid()) {
            if ($post["remove_image"] == 1) {
                $pageService->removePageImage($page->getFeaturedImage());
                $page->setFeaturedImage(null);
            }
            $pageService->persistData($page, $post["page"]["featured_image_file"]);
            $this->sendFlashMessage($this->translate("The page has been saved."), "success");
            $view = new ViewModel(array(
                "success" => true,
            ));
            $view->setTemplate('documentation/page/edit-page.phtml');

            return $view;
        } else {
            return $this->displayForm($pageForm, $page);
        }
    }

    /**
     * Display Equipment taxonomy form
     * 
     * @param type $taxonomyForm
     * @return ViewModel $view
     */
    private function displayForm($form, $page)
    {
        $viewValues = array(
            'featuredImage' => $page->getFeaturedImage(),
            'form' => $form
        );
        $view = new ViewModel($viewValues);
        $view->setTemplate('documentation/page/edit-page.phtml');
        return $view;
    }

    /**
     * @return \Documentation\Service\PageService
     */
    private function getPageService()
    {
        return $this->getService('Documentation\Service\PageService');
    }

    /**
     * @return \Application\Service\SectionService
     */
    private function getPageSectionService()
    {
        return $this->getService('Documentation\Service\PageSectionService');
    }

    /**
     * @return \Application\Service\SectionService
     */
    private function getPageInlineSectionService()
    {
        return $this->getService('Documentation\Service\PageInlineSectionService');
    }

    /**
     * @return \Documentation\Service\HtmlContentService
     */
    private function getHtmlContentPageSectionService()
    {
        return $this->getService('Documentation\Service\HtmlContentPageSectionService');
    }

    /**
     * @return \Documentation\Service\HtmlContentService
     */
    private function getHtmlContentPageInlineSectionService()
    {
        return $this->getService('Documentation\Service\HtmlContentPageInlineSectionService');
    }

    /**
     * @param $pageId
     * @return \Documentation\Entity\Page
     */
    private function getPage($pageId)
    {
        $page = $this->getPageService()
                        ->getPage($pageId);
        return $page;
    }

}
