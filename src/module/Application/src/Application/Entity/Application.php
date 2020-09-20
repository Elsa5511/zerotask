<?php

namespace Application\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;

class Application extends Entity
{

    /**
     *  The name of the application.
     * @var string 
     */
    protected $name;

    /**
     * The slug or keyname of the application
     * @var string
     */
    protected $slug;
    
    /**
     * The name of the application directory (vendor\Vidum\$directory)
     * @var string 
     */
    protected $directory;

    /**
     * This variable is needed for check if the feature images for categories should be showed
     * @var boolean
     */
    protected $show_category_feature_image;

    /**
     * This variable is needed for check if the feature images for equipments should be showed
     * @var boolean
     */
    protected $show_equipment_feature_image;
    
    /**
     *
     * @var array
     */
    protected $home;
    
    /**
     *
     * @var array 
     */
    protected $features;

    /**
     *
     * @var int 
     */
    protected $rootCategoryId;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }
    
    public function getDirectory() {
        return $this->directory;
    }

    public function setDirectory($directory) {
        $this->directory = $directory;
    }

    public function getShowCategoryFeatureImage() {
        return $this->show_category_feature_image;
    }

    public function setShowCategoryFeatureImage($show_category_feature_image) {
        $this->show_category_feature_image = $show_category_feature_image;
    }

    public function getShowEquipmentFeatureImage() {
        return $this->show_equipment_feature_image;
    }

    public function setShowEquipmentFeatureImage($show_equipment_feature_image) {
        $this->show_equipment_feature_image = $show_equipment_feature_image;
    }

    public function setHome($home)
    {
        $this->home = $home;
        return $this;
    }

    public function getHome()
    {
        return $this->home;
    }

    public function setFeatures(array $features)
    {
        $this->features = $features;
        return $this;
    }
    
    public function getFeatures(){
        return $this->features;
    }

    public function setRootCategoryId($rootCategoryId)
    {
        $this->rootCategoryId = (int) $rootCategoryId;
        return $this;
    }

    public function getRootCategoryId()
    {
        return $this->rootCategoryId;
    }

}