<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;
use Application\Utility\ServiceMessageToFlashMessageConverter;
use Application\Utility\FlashMessage;

class LocationController extends AbstractBaseController {

    /**
     * List of Locations
     * 
     */
    public function indexAction() {
        $locations = $this->getLocationService()->fetchAll();
        $title = $this->getAdminTitle() . $this->getTranslator()->translate('Locations');
        return new ViewModel(array(
            'locations' => $locations,
            'title' => $title,
        ));
    }

    /**
     * Add Location Form
     *
     * @return ViewModel $view
     */
    public function addAction() {
        $location = $this->getNewLocation();
        $locationForm = $this->getLocationForm($location);

        $request = $this->getRequest();
        if ($request->isPost()) {
            return $this->storePostData($request->getPost(), $locationForm);
        } else {
            return $this->displayForm($locationForm);
        }
    }

    /**
     * Display Location form
     * 
     * @param type $locationForm
     * @return ViewModel a view
     */
    private function displayForm($locationForm, $locationId = 0) {
        $viewValues = array(
            'locationId' => $locationId,
            'form' => $locationForm
        );
        if ($locationId == 0) {
            $view = new ViewModel($viewValues);
            $view->setTemplate('application/location/edit.phtml');
            return $view;
        } else {
            return $viewValues;
        }
    }

    /**
     * Edit Location Action
     * 
     */
    public function editAction() {
        $locationId = $this->getEvent()
                        ->getRouteMatch()->getParam('id', false);
        $location = $this->getLocation($locationId);
        if ($location) {
            $locationForm = $this->getLocationForm($location);
            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->storePostData($request->getPost(), $locationForm);
            } else {
                return $this->displayForm($locationForm, $locationId);
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectTo('index');
        }
    }

    /**
     * Validate the post data, then store it
     * or return a validation message
     * 
     * @param type $post
     * @param type $locationForm
     * @return redirects or display the form
     */
    private function storePostData($post, $locationForm) {
        $locationForm->setData($post);
        $location = $locationForm->getObject();
        $isFormValid = $locationForm->isValid();
        if ($isFormValid &&
                $this->getLocationService()->isLocationUnique($location)) {
            $this->saveLocationData($location);
            return $this->redirectTo('index');
        } else {
            if ($isFormValid) {
                $this->sendFlashMessage("This location already exists", "error");
            }
            return $this->displayForm($locationForm, $location->getLocationTaxonomyId());
        }
    }

    public function deleteAction() {
        $locationId = $this->params()->fromRoute('id', 0);
        if ($locationId > 0) {
            $deleteResultFlashMessage = $this->tryToDeleteLoaction($locationId);
            $this->sendFlashMessageFrom($deleteResultFlashMessage);
        } else {
            $this->displayGenericErrorMessage();
        }
        return $this->redirectTo('index');
    }

    private function tryToDeleteLoaction($locationId) {
        try {
            $serviceMessage = $this->getLocationService()->deleteById($locationId);
            return ServiceMessageToFlashMessageConverter::convert($serviceMessage);
        } catch (\Application\Service\ServiceOperationException $exception) {
            return new FlashMessage('error', $exception->getMessage());
        }
    }

    /**
     * Delete many Locations
     * Call by ajax 
     * 
     * @return a success message
     */
    public function deleteManyAction() {
        $post = $this->getRequest()->getPost();
        $locationIds = explode(',', $post->delete_list);
        $serviceMessageArray = $this->getLocationService()->deleteByIds($locationIds);
        foreach ($serviceMessageArray as $serviceMessage) {
            $deleteResultFlashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
            $this->sendFlashMessageFrom($deleteResultFlashMessage);
        }
        return $this->redirectTo('index');
    }

    public function searchAction() {
        $term = isset($_GET['term']) ? $_GET['term'] : "";
        $locations = $this->getLocationCacheService()->searchValuesByApplicationAndSlug($this->getApplicationName(), $term);
        die(json_encode($locations));
    }

    /**
     * @return \Application\Service\Cache\LocationCacheService
     */
    protected function getLocationCacheService() {
        return $this->getServiceLocator()->get('Application\Service\Cache\LocationCacheService');
    }

    /**
     * Get a location entity object
     * 
     * @param type $id <optional>
     * @return type
     */
    private function getLocation($id) {
        $location = $this->getLocationService()->getLocation($id);
        return $location;
    }

    /**
     * Get a new location entity object
     * 
     * @return type
     */
    private function getNewLocation() {
        $location = new \Application\Entity\LocationTaxonomy();
        return $location;
    }

    private function saveLocationData($location) {
        $resultId = $this->getLocationService()->persistData($location);
        if ($resultId > 0) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Location has been successfully saved.'), "success");
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Location could not be saved at this time.'), "error");
        }
    }

    private function getLocationForm($location) {
        $formFactory = $this->getFormFactory();
        $locationForm = $formFactory->createLocationForm();
        $locationForm->bind($location);
        return $locationForm;
    }

    private function redirectTo($action) {
        return $this->redirectToPath('location', $action);
    }

    private function getLocationService() {
        return $this->getService('Application\Service\LocationService');
    }

}
