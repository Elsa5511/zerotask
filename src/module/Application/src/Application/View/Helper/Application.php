<?php

namespace Application\View\Helper;

use Zend\Mvc\Router\Http\RouteMatch;
use Zend\View\Helper\AbstractHelper;
use Equipment\Controller\Helper\ConfigFieldsHelper;

class Application extends AbstractHelper {

    protected $routeMatch;
    protected $serviceLocator;

    public function __construct(RouteMatch $routeMatch, $serviceLocator) {
        $this->routeMatch = $routeMatch;
        $this->serviceLocator = $serviceLocator;
    }

    public function getRouteName() {
        if (strpos($this->routeMatch->getMatchedRouteName(), 'wildcard')) {
            return $this->routeMatch->getMatchedRouteName();
        }

        return $this->routeMatch->getMatchedRouteName() . '/wildcard';
    }

    public function url($params = array(), $options = array(), $reuseMatchedParams = false) {
        return $this->getView()->url($this->getRouteName(), array_merge($params, array(
                    'application' => $this->routeMatch->getParam('application'),
                        )), $options, $reuseMatchedParams);
    }

    /**
     * Check if a slug (or array of slugs) from a url, should be shown. This will 
     * depend of the current application features
     * @param array|string $slug
     * @return boolean
     */
    public function showUrlBySlug($slug) {
        $application = $this->routeMatch->getParam('application');
        $features = $this->getApplicationFeatureService()->getApplicationFeatures($application);
        $enabled = false;
        foreach ($features as $feature) {
            if (is_array($slug)) {
                if (in_array($feature->getSlug(), $slug)) {
                    $enabled = true;
                    break;
                }
            } else {
                if ($feature->getSlug() == $slug) {
                    $enabled = true;
                    break;
                }
            }
        }
        return $enabled;
    }

    public function isFieldUsedInEquipment($fieldName)
    {
        $application = $this->routeMatch->getParam('application');
        $configFieldsHelper = new ConfigFieldsHelper($this->getServiceLocator());
        $fieldsToShow = $configFieldsHelper->getEquipmentFieldsByApplication($application);
        return in_array($fieldName, $fieldsToShow);
    }

    public function name() {
        return strtoupper($this->routeMatch->getParam('application'));
    }

    /**
     * Check if the breadcrumbs should be shown, depends if the templates loaded 
     * have an error (error 404, forbidden, etc)
     * @return boolean
     */
    public function showBreadcrumbs() {
        $showBreadcrumbs = true;
        $children = $this->getView()->viewmodel()->getCurrent()->getChildren();
        if (is_array($children)) {
            foreach ($children as $child) {
                $variables = $child->getVariables();
                if (array_key_exists('error', $variables)) {
                    $showBreadcrumbs = false;
                    break;
                }
            }
        }
        return $showBreadcrumbs;
    }
    
    /**
     * Check if a date is expired related to now
     * @param DateTime $date
     * @return boolean
     */
    public function isDateExpired($date){
        $expiration = strtotime($date);
        $now = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        return ($now > $expiration);
    }

    public function getDirectory() {
        $application = $this->routeMatch->getParam('application');
        $applicationService = $this->getApplicationService();
        $applicationEntity = $applicationService->getApplication($application);
        if ($applicationEntity !== null) {
            return $applicationEntity->getDirectory();
        } else {
            return null;
        }
    }

    /* For guest access - se acl.local.php.dist */
    public function hasGuestAccess() {
        $config = $this->getServiceLocator()->get('Config');
        if(isset($config['guest_access_applications'])) {
            $guestAccessApplications = $config['guest_access_applications'];
            return in_array(strtolower($this->name()), $guestAccessApplications);
        } else {
            return false;
        }
    }

    private function getServiceLocator() {
        return $this->serviceLocator;
    }

    private function getApplicationFeatureService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationFeatureService');
    }

    private function getApplicationService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationService');
    }

    public function __toString() {
        return $this->name();
    }

}
