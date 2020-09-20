<?php

namespace Documentation\Service;

use Application\Utility\Image;

class PageService extends \Acl\Service\AbstractService
{

    const PATH_PAGE_CONTENT = './public/content/page/';

    public function getNewPage($parentId)
    {
        $page = new \Documentation\Entity\Page();
        if ($parentId == 0) {
            return $page;
        }
        $equipmentTaxonomyService = $this->getDependency('equipmentTaxonomyService');
        $parentTaxonomy = $equipmentTaxonomyService->findById($parentId);

        $page->setCategory($parentTaxonomy);
        return $page;
    }

    public function deleteById($pageId)
    {
        $page = $this->getEntityRepository()->find($pageId);
        if ($page) {
            if ($this->hasSection($page)) {

                $nameSpace = "error";
                $message = $this->translate('Page contains sections. Remove them before continuing.');
            } else {
                $this->removePageImage($page->getFeaturedImage());
                $this->remove($page);
                $nameSpace = "success";
                $pageName = $page->getName();
                $message = $pageName . ' ' . $this->translate('has been deleted successfully.');
            }
        } else {
            $nameSpace = "error";
            $message = $this->translate('Page doesn\'t exist');
        }

        return array(
            'namespace' => $nameSpace,
            'message' => $message
        );
    }

    public function getPage($pageId)
    {
        $page = $this->getEntityRepository()->find($pageId);
        return $page;
    }

    public function listPagesByCategory($categoryId)
    {
        return $this->getEntityRepository()->findBy(array('category' => $categoryId));
    }

    public function persistData($page, $featuredImageData)
    {
        $isUploading = !empty($featuredImageData['tmp_name']);
        if ($isUploading) {
            $newImageName = $this->resizeImage($featuredImageData, $page->getFeaturedImage());
            $page->setFeaturedImage($newImageName);
        }

        parent::persist($page);
    }

    public function removePageImage($featuredImage)
    {
        if ($featuredImage !== '' && $featuredImage !== null) {
            $source = self::PATH_PAGE_CONTENT . $featuredImage;
            if (file_exists($source)) {
                unlink($source);
            }
        }
    }

    /**
     * Checks of a page has section
     * 
     * @param \Documentation\Entity\Page $page
     * @return boolean
     */
    private function hasSection(\Documentation\Entity\Page $page)
    {
        return $this->getEntityRepository()->hasSection($page);
    }

    private function resizeImage($featuredImageData, $currentImage)
    {
        $folderPath = self::PATH_PAGE_CONTENT;
        $image = new Image();
        if ($currentImage) {
            $image->deleteImage($folderPath . $currentImage);
        }
        $newImage = $image->resizeImage(
                $featuredImageData['tmp_name'], $this->image['width'], $folderPath . $featuredImageData['name']);
        return $newImage;
    }

    private function getEntityRepository()
    {
        return $this->getRepository('Documentation\Entity\Page');
    }

}