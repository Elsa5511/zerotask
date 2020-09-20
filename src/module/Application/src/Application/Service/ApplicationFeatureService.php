<?php

namespace Application\Service;

use Sysco\Aurora\Service\Service;
use Application\Entity\ApplicationFeature;
use Zend\Stdlib\Hydrator\ClassMethods;

class ApplicationFeatureService extends Service
{

    protected $applications;
    
    protected $features;
    
    /**
     * Return the list of the features per application.
     * @return array List of application features
     */
    public function getApplicationFeatures($application, $featureOverrides = array()) {
        $applicationFeatureArray = array();
        $hydrator = new ClassMethods(true);

        $features = array();
        if ($application === 'ladoc' && count($featureOverrides) > 0) {
            foreach ($featureOverrides as $feature) {
                array_push($features, $feature->getKey());
            }
        }
        else if (array_key_exists($application, $this->applications)) {
            $features = $this->applications[$application]['features'];
        }

        foreach ($features as $feature) {
            $applicationFeatureEntity = $hydrator->hydrate($this->features[$feature], new ApplicationFeature());
            $applicationFeatureEntity->setSlug($feature);
            array_push($applicationFeatureArray, $applicationFeatureEntity);
        }

        return $applicationFeatureArray;
    }

//    public function

    public function getApplicationFeatureByKey($application, $key)
    {
        $hydrator = new ClassMethods(true);

        $features = array();
        if (array_key_exists($application, $this->applications)) {
            $features = $this->applications[$application]['features'];
        }

        foreach ($features as $feature)
            if($key == $feature) {
                $applicationFeatureEntity = $hydrator->hydrate($this->features[$feature], new ApplicationFeature());
                $applicationFeatureEntity->setSlug($feature);
                return $applicationFeatureEntity;
            }

        return null;
    }
}
