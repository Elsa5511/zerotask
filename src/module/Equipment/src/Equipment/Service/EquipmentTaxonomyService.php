<?php

namespace Equipment\Service;

use Application\Service\AbstractBaseService;
use Application\Utility\Image;
use Equipment\Repository\EquipmentTaxonomy;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentTaxonomyService extends AbstractBaseService implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    protected $equipmentRelationship = array();
    protected $pageRelationship = array();
    protected $message = array();

    /**
     * @return EquipmentTaxonomy
     */
    protected function getEntityRepository() {
        return $this->getRepository('Equipment\Entity\EquipmentTaxonomy');
    }

    /**
     * @return \Equipment\Service\Cache\EquipmentTaxonomyCacheService
     */
    private function getEquipmentTaxonomyCacheService() {
        return $this->getServiceLocator()->get('Equipment\Service\Cache\EquipmentTaxonomyCacheService');
    }

    /**
     * Set serviceManager instance
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
        return $this->serviceLocator;
    }

    public function getFormOptions($criteria = array())
    {
        $options = array();
        $entities = $this->fetchEquipmentTaxonomy($criteria);
        foreach ($entities as $item) {
            $options[$item->getEquipmentTaxonomyId()] = $item->getName();
        }
        return $options;
    }

    private function resizeImage($imagePost, $currentImage)
    {
        $folderPath = "public/content/equipment_taxonomy/";
        $image = new Image();
        if($currentImage) {
            $image->deleteImage($folderPath . $currentImage);
        }
        $newImage = $image->resizeImage(
                        $imagePost['tmp_name'],
                        $this->image['width'],
                        $folderPath . $imagePost['name']);
        return $newImage;
    }

    public function fetchAll() {
        $criteria = array('status' => 'active');
        $entities = $this->getEntityRepository()->findBy($criteria);        
        return $entities;
    }
    
    public function getAvailableEquipmentTaxonomy($equipmentTaxonomyId)
    {
        if ($equipmentTaxonomyId > 0) {
            $equipmentTaxonomy = $this->findById($equipmentTaxonomyId);
            $potencialChildren = $this->getEntityRepository()
                    ->fetchPotentialChildren($equipmentTaxonomyId);
            $equipmentTaxonomyParents = $this->getParents($equipmentTaxonomy);
        } else {
            $potencialChildren = $this->fetchEquipmentTaxonomy(array('status' => 'active'));
            $equipmentTaxonomyParents = array();
        }

        $availableEquipmentTaxonomies = array();

        foreach ($potencialChildren as $potencialChild) {
            $potencialChildId = $potencialChild->getEquipmentTaxonomyId();
            $isSuperiorLevel = $this->isSuperiorLevel($potencialChildId, $equipmentTaxonomyParents);

            if (!$isSuperiorLevel) {
                $availableEquipmentTaxonomies[$potencialChildId] =
                        $potencialChild->getName();
            }
        }
        return $availableEquipmentTaxonomies;
    }

    private function isSuperiorLevel($potencialChildId, $parentIds)
    {
        return in_array($potencialChildId, $parentIds);
    }

    private function getParents($equipmentTaxonomy)
    {
        $parents = array();
        if ($equipmentTaxonomy->getParentId() > 0) {
            $parent = $this->findById($equipmentTaxonomy->getParentId());
            $parents = $this->getParents($parent);
            array_push($parents, $parent->getEquipmentTaxonomyId());            
        } 
        return $parents;
    }

    private function deleteEquipmentTaxonomy($equipmentTaxonomy)
    {
        $this->removeTaxonomyImage($equipmentTaxonomy->getFeaturedImage());
        $this->remove($equipmentTaxonomy);
        $this->getEquipmentTaxonomyCacheService()->loadCacheData();
        return $this->translate('Category "%s" was removed successfully.');
    }

    public function deleteById($equipmentTaxonomyId)
    {
        $namespace = "error";
        $equipmentTaxonomy = $this->findById($equipmentTaxonomyId);

        if ($equipmentTaxonomy) {
            $entitiesRelated = $this->getEntitiesRelated($equipmentTaxonomyId);
            $isRelated = count($entitiesRelated) > 0;

            if ($isRelated) {
                $message = $this->getRelationshipErrorMessage($entitiesRelated);
            } else {
                $namespace = "success";
                $message = $this->deleteEquipmentTaxonomy($equipmentTaxonomy);              
            }
            $message = sprintf($message, $equipmentTaxonomy->getName());

        } else {
            $message = $this->translate('Category doesn\'t exist');
        }

        return array(
            "namespace" => $namespace,
            "message" => $message
        );
    }

    public function removeTaxonomyImage($featuredImage)
    {
        if ($featuredImage !== '' && $featuredImage !== null) {
            $source = './public/content/equipment_taxonomy/' . $featuredImage;
            if (file_exists($source)) {
                unlink($source);
            }
        }
    }
    
    // TODO validate if parent category is updated (levels)
    public function persistData($taxonomy, $postData)
    {
        $imagePost = $postData['featured_image_file'];
        if (!empty($imagePost['tmp_name'])) {
            $newImageName = $this->resizeImage($imagePost,
                                                $taxonomy->getFeaturedImage());
            $taxonomy->setFeaturedImage($newImageName);
        }
        $taxonomy->setType('category');
        $taxonomy->setLevel();
        $this->updateLevels($taxonomy);
        parent::persist($taxonomy);

        $this->getEquipmentTaxonomyCacheService()->loadCacheData();
    }
    
    private function updateLevels($taxonomy)
    {
        if ($taxonomy) {
            $children = $this->fetchEquipmentTaxonomy(
                    array('parent' => $taxonomy->getEquipmentTaxonomyId())
            );
            foreach ($children as $child) {
                if ($child) {
                    $child->setLevel();
                    $this->updateLevels($child);
                }
            }
        }
    }

    /**
     * 
     * @param type $parentId
     * @return \Equipment\Entity\EquipmentTaxonomy
     */
    public function getNewTaxonomy($parentId)
    {
        $parentTaxonomy = $this->findById($parentId);
        $taxonomy = new \Equipment\Entity\EquipmentTaxonomy();
        $taxonomy->setParent($parentTaxonomy);
        return $taxonomy;
    }
    
    public function fetchEquipmentTaxonomy($args = array(), $orderBy = array('name' => 'ASC'))
    {
        return $this->getEntityRepository()->findBy($args, $orderBy);
    }

    public function fetchEquipmentTaxonomyHierarchy(array $criteria, array $orderBy = null)
    {
        return $taxonomies = $this->getEntityRepository()->findBy($criteria, $orderBy);
    }

    public function getEquipmentRelationship()
    {
        return $this->equipmentRelationship;
    }

    public function setEquipmentRelationship($equipmentRelationship)
    {
        $this->equipmentRelationship = $equipmentRelationship;
    }

    public function getPageRelationship()
    {
        return $this->pageRelationship;
    }

    public function setPageRelationship($pageRelationship)
    {
        $this->pageRelationship = $pageRelationship;
    }

}

