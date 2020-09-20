<?php

namespace Equipment\Controller;

use Application\Controller\Helper\DeactivationHelper;
use Equipment\Constants\SearchValues;
use Equipment\Controller\Helper\AdvancedSearchHelper;
use Equipment\Entity\Equipment;
use Equipment\Entity\EquipmentInstance;
use Equipment\Entity\InstanceExpirationFieldTypes;
use Equipment\Service\EquipmentInstanceContainerService;
use Equipment\Service\EquipmentInstanceReportService;
use Equipment\Service\EquipmentInstanceService;
use Equipment\Service\EquipmentService;
use Equipment\Service\EquipmentTaxonomyService;
use Equipment\Service\PeriodicControlService;
use Equipment\Service\VisualControlService;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Application\Controller\AbstractBaseController;

/**
 * This controller is related to equipment instances
 *
 */
class EquipmentInstanceController extends AbstractBaseController {

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $actionName = $controller->params()->fromRoute('action');
            $applicationName = $controller->params()->fromRoute('application');
            if (in_array($actionName, array('index', 'detail', 'view-last-periodic-control', 'do-search', 'do-control-search'))) {
                $instancesFeature = $controller->getApplicationFeatureService()->getApplicationFeatureByKey($applicationName, 'instances');
                if ($instancesFeature)
                    $destinationController = 'Controller\EquipmentInstance';
                else
                    $destinationController = 'Controller\Equipment';
                $searchForms = $controller->forward()->dispatch($destinationController, array('action' => 'advanced-search', 'application' => $applicationName));
                $controller->layout()->addChild($searchForms, 'searchForms');
            }
        }, -100); // execute after executing action logic

        return $this;
    }

    public function indexAction() {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);

        // There is no way to modify the inital index action of the instance,
        // so we redirect here instead.
        if ($equipment->getInstanceType() === Equipment::INSTANCE_TYPE_CONTAINER
            && !($this instanceof EquipmentInstanceContainerController)
        ) {
            return $this->redirectToPath("equipment-instance-container", "index",
                array('id' => $equipmentId));
        }

        if (empty($equipment)) {
            $this->flashMessenger()
                ->setNamespace('error')
                ->addMessage($this->getTranslator()->translate("Equipment doesn't exist"));
            return $this->redirectToEquipment();
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);

        $equipmentName = $equipment->getTitle();
        $equipmentIntervalDays = $equipment->getControlIntervalByDays();
        $service = $this->getEquipmentInstanceService($equipment->getInstanceType());
        $equipmentInstances = $service->getEquipmentInstanceBelongEquipment($equipment, $this->getOrganizationIfRestrictionIsEnabled());

        $viewModel = new ViewModel(array(
            'equipmentName' => $equipmentName,
            'equipmentInstances' => $equipmentInstances,
            'equipmentId' => $equipmentId,
            'equipmentIntervalDays' => $equipmentIntervalDays,
            'controller' => $this->getControllerName()
        ));
        $viewModel->setTemplate('equipment/equipment-instance/index');
        return $viewModel;
    }

    protected function getControllerName() {
        return 'equipment-instance';
    }


    public function exportPeriodicControlReportAction() {
        return $this->exportExpiredAction(InstanceExpirationFieldTypes::PERIODIC_CONTROL);
    }

    public function getExportTypeFromPost() {
        $post = $this->getRequest()->getPost();
        $exportType = $post->get('exportType');
        return $exportType;
    }

    public function exportExpiredLifetimeReportAction() {
        return $this->exportExpiredAction(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME);
    }

    public function exportExpiredGuaranteeReportAction() {
        return $this->exportExpiredAction(InstanceExpirationFieldTypes::GUARANTEE);
    }

    private function exportExpiredAction($type) {
        $reportLevel = $this->params()->fromRoute('report_level');
        $id = $this->params()->fromRoute('id');

        $reportService = $this->getEquipmentInstanceReportService();
        $expiredInstances = $this->getEquipmentInstanceService()
            ->getAllExpired($type, $reportLevel, $id);
        $report = $reportService->createExpiredReportTable($type, $expiredInstances);
        $this->exportReport($report, $this->getExportTypeFromPost());
        return $this->redirectToReferer();
    }

    public function detailAction() {
        $equipmentInstanceId = (int)$this->params()->fromRoute('id', 0);
        $possibleContainerInstance = $this->getEquipmentInstanceContainerService()->findById($equipmentInstanceId);

        // This will redirect to the container instance when coming back from breadcrumbs.
        if ($possibleContainerInstance && !($this instanceof EquipmentInstanceContainerController)) {
            return $this->redirectToPath("equipment-instance-container", "detail",
                array('id' => $equipmentInstanceId));
        }

        $equipmentInstance = $this->getEquipmentInstanceService()->getEquipmentInstance($equipmentInstanceId);

        if ($equipmentInstance) {
            $equipment = $equipmentInstance->getEquipment();

            $translator = $this->getTranslator();
            if (empty($equipmentInstance)) {
                $this->flashMessenger()
                    ->setNamespace('error')
                    ->addMessage($translator->translate("Equipment instance doesn't exist"));
                return $this->redirectToEquipment();
            }


            $this->setBreadcrumbForFeatureActions($equipment, $this->getControllerName());
            $checkout = $this->getCheckoutService()
                ->getLastCheckout($equipmentInstanceId);

            $subinstances = $this->getEquipmentInstanceService()
                ->getSubinstancesByParentId($equipmentInstanceId);

            $view = new ViewModel(array(
                'equipmentType' => $equipment,
                'instance' => $equipmentInstance,
                'checkoutId' => empty($checkout) ? null : $checkout->getCheckoutId(),
                'subinstances' => $subinstances,
                'periodicControls' => $equipmentInstance->getPeriodicControls(),
                'visualControls' => $equipmentInstance->getVisualControls(),
                'instanceId' => $equipmentInstanceId,
                'currentUserObject' => $this->getCurrenUser()
            ));

            $attachmentTable = $this->forward()->dispatch('Controller\EquipmentInstanceAttachment',
                array('action' => 'attachment-table', 'id' => $equipmentInstanceId)
            );

            $view->addChild($attachmentTable, 'attachmentTable');
            $view->setTemplate('equipment/equipment-instance/detail.phtml');
            return $view;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function setBreadcrumbForEquipmentDetail($equipment, $equipmentInstanceId) {
        $applicationName = $this->params()->fromRoute('application');
        $this->setBreadcrumbForFeatureActions($equipment, 'equipment-instance');
        $navigationPage = $this->getNavigationPage('equipment-instance-detail');
        $navigationPage->setParams(
            array(
                'application' => $applicationName,
                'id' => $equipmentInstanceId
            )
        );
    }

    public function viewLastPeriodicControlAction() {
        $peridicControlId = (int)$this->params()->fromRoute('id', 0);
        $periodicControl = $this->getPeriodicControlService()->getPeriodicControl($peridicControlId);
        $equipmentInstanceId = (int)$this->params()->fromRoute('equipmentInstanceId', 0);

        if (empty($periodicControl)) {
            $this->sendFlashMessage("Periodic Control doesn't exist", 'error');
            return $this->redirectToEquipmentInstance('detail', $equipmentInstanceId);
        }

        $equipmentInstance = $periodicControl->getEquipmentInstance();
        $instanceSerialNumber = $equipmentInstance->getSerialNumber();
        $equipmentType = $equipmentInstance->getEquipment();

        $this->setBreadcrumbForEquipmentDetail($equipmentType, $equipmentInstanceId);

        $view = new ViewModel(array(
            'title' => $this->getTranslator()->translate('Periodic control') . ': ' . $instanceSerialNumber,
            'equipmentType' => $equipmentType,
            'instance' => $equipmentInstance,
            'periodicControl' => $periodicControl,
        ));

        $attachmentTable = $this->forward()->dispatch('Controller\PeriodicControlAttachment'
            , array('action' => 'attachment-table', 'id' => $peridicControlId)
        );
        $view->addChild($attachmentTable, 'attachmentTable');
        return $view;
    }

    public function viewLastVisualControlAction() {
        $equipmentInstanceId = (int)$this->params()->fromRoute('id', 0);
        $lastVisualControl = $this->getVisualControlService()->getLastVisualControl($equipmentInstanceId);
        if (empty($lastVisualControl)) {
            $this->sendFlashMessage("Visual Control doesn't exist", 'error');
            return $this->redirectToEquipmentInstance('detail', $equipmentInstanceId);
        }

        $equipmentInstance = $lastVisualControl->getEquipmentInstance();
        $equipment = $equipmentInstance->getEquipment();

        $this->setBreadcrumbForEquipmentDetail($equipment, $equipmentInstanceId);

        $stubInstanceSerialNumber = $equipmentInstance->getSerialNumber();
        return array(
            'title' => $this->getTranslator()->translate('Visual control') . ': ' . $stubInstanceSerialNumber,
            'equipmentType' => $equipment,
            'instance' => $equipmentInstance,
            'visualControl' => $lastVisualControl,
        );
    }

    public function deactivateAction() {
        $equipmentId = $this->params()->fromRoute('equipment_id', 0);
        $equipmentInstanceId = $this->params()->fromRoute('id', 0);

        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateAction($equipmentInstanceId);

        if ($equipmentId > 0)
            return $this->redirectToEquipmentInstance('index', $equipmentId);
        else
            return $this->redirectToReferer();
    }

    public function activateAction() {
        $equipmentId = $this->params()->fromRoute('equipment_id', 0);
        $equipmentInstanceId = $this->params()->fromRoute('id', 0);

        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->activateAction($equipmentInstanceId);

        if ($equipmentId > 0)
            return $this->redirectToEquipmentInstance('index', $equipmentId);
        else
            return $this->redirectToReferer();
    }

    private function getDeactivationHelper() {
        return new DeactivationHelper($this, $this->getEquipmentInstanceService(), $this->getTranslator());
    }

    public function unlinkAction() {
        $equipmentToUnlinkId = $this->params()->fromRoute('id', 0);
        $equipmentInstanceParentId = $this->params()->fromRoute('parent_id', 0);

        if ($equipmentToUnlinkId != 0) {
            $flashMessengerArray = $this->getEquipmentInstanceService()->unlinkSubinstnace($equipmentToUnlinkId);
            $this->updateMinDatesRelated(array($equipmentInstanceParentId));

            $this->sendFlashMessage($flashMessengerArray['message'], $flashMessengerArray['namespace'], true);
        }
        else {
            $this->sendFlashMessage('Incorrect Equipment instance id', 'error', true);
        }

        return $this->redirectToEquipmentInstance('detail', $equipmentInstanceParentId);
    }

    public function deactivateManyAction() {
        $post = $this->getRequest()->getPost();
        $ids = $post->idList;
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateManyAction($ids);

        return $this->redirectToReferer();
    }

    public function addAction() {
        $equipmentId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $equipment = $this->getEquipmentService()->checkEquipmentExists($equipmentId);
        if ($equipment) {
            $this->setBreadcrumbForFeatureActions($equipment, $this->getControllerName());
            $equipmentInstance = $this->createNewEquipmentInstance();
            $equipmentInstance->setEquipment($equipment);
            $form = $this->getEquipmentInstanceForm($equipmentInstance);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                $equipmentId = $this->saveEquipmentInstance($form, $post);
                if ($equipmentId) {
                    return $this->redirectToEquipmentInstance('index', $equipmentId);
                }
            }
            $this->setRegisterNumberForNewForm($form);
            $viewValues = array(
                'form' => $form,
                'equipment' => $equipment,
                'controller' => $this->getControllerName()
            );
            $view = new ViewModel($viewValues);
            $view->setTemplate('equipment/equipment-instance/edit.phtml');
            return $view;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }

    }

    public function copyAction() {
        $equipmentInstanceId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $equipmentInstance = $this->getEquipmentInstanceService()->checkEquipmentInstanceExists(
            $equipmentInstanceId);

        if ($equipmentInstance) {
            $this->setBreadcrumbForFeatureActions($equipmentInstance->getEquipment(),
                $this->getControllerName());

            $equipmentInstance->setEquipmentInstanceId(null);
            $equipmentInstance->setSerialNumber(null);
            $form = $this->getEquipmentInstanceForm($equipmentInstance);
            $this->setRegisterNumberForNewForm($form);
            $form->setAttribute('action', $this->url()->fromRoute('base/wildcard',
                array(
                    'application' => $this->params()->fromRoute('application'),
                    'controller' => $this->getControllerName(),
                    'action' => 'add',
                    'id' => $equipmentInstance->getEquipment()->getEquipmentId()
                ))
            );

            $viewModel = new ViewModel(array(
                'controller' => $this->getControllerName(),
                'form' => $form,
                'equipment' => $equipmentInstance->getEquipment()
            ));
            $viewModel->setTemplate('equipment/equipment-instance/edit.phtml');
            return $viewModel;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    /**
     * Edit Equipment Instance Action
     *
     */
    public function editAction() {
        $equipmentInstanceId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $equipmentInstance = $this->getEquipmentInstanceService()->checkEquipmentInstanceExists(
            $equipmentInstanceId);

        if ($equipmentInstance) {
            $this->setBreadcrumbForFeatureActions($equipmentInstance->getEquipment(),
                $this->getControllerName());
            $form = $this->getEquipmentInstanceForm($equipmentInstance);
            $request = $this->getRequest();
            if ($request->isPost()) {
                $post = $request->getPost();
                $equipmentId = $this->saveEquipmentInstance($form, $post, false);
                if ($equipmentId) {
                    return $this->redirectToEquipmentInstance('index', $equipmentId);
                }
            }
            $viewModel = new ViewModel(array(
                'equipmentInstanceId' => $equipmentInstanceId,
                'form' => $form,
                'equipment' => $equipmentInstance->getEquipment()
            ));
            $viewModel->setTemplate('equipment/equipment-instance/edit.phtml');
            return $viewModel;
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    /**
     * @return EquipmentInstance
     */
    protected function createNewEquipmentInstance() {
        return new EquipmentInstance();
    }

    public function updateManyAction() {
        $equipmentIntance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstanceForm = $this->getEquipmentInstanceEditManyForm($equipmentIntance);
        $equipmentId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $equipment = $this->getEquipmentService()->checkEquipmentExists($equipmentId);

        if ($equipment) {
            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->storeManyEquipmentInstances($request->getPost(), $equipmentInstanceForm, $equipmentId);
            }
            else {
                $this->sendFlashMessage("The action could not be processed, try again later ", "error");
                return $this->redirectToEquipmentInstance('index', $equipmentId);
            }
        }
        $this->sendFlashMessage("The equipment doesn't exist ", "error");
        return $this->redirectToEquipmentInstance('index', $equipmentId);
    }

    private function storeManyEquipmentInstances($post, $equipmentInstanceForm, $equipmentId) {
        $equipmentInstanceForm->setData($post);

        $equipmentInstanceIdsSerialized = $equipmentInstanceForm->get('equipment-instance')->get('listEquipmentInstances')->getValue();

        if ($equipmentInstanceForm->isValid()) {
            $equipmentInstanceToArray = $equipmentInstanceForm->get('equipment-instance')
                ->getHydrator()
                ->extract($equipmentInstanceForm->getObject());
            $updateVisualControlOption = $post['equipment-instance']['checkUpdate'];
            $equipmentInstanceIdsUnserialized = unserialize($equipmentInstanceIdsSerialized);

            $this->getEquipmentInstanceService()->updateMany($equipmentInstanceToArray, $equipmentInstanceIdsUnserialized, $updateVisualControlOption);

            $this->updateMinDatesRelated($equipmentInstanceIdsUnserialized);

            $this->sendFlashMessage("The instances has been saved successfully.", "success");
            return $this->redirectToEquipmentInstance('index', $equipmentId);
        }
        else {
            $equipment = $this->getEquipmentService()->checkEquipmentExists($equipmentId);
            return $this->displayEditManyForm($equipment, $equipmentInstanceIdsSerialized, $equipmentInstanceForm);
        }
    }

    private function displayEditManyForm($equipment, $equipmentInstanceIdsSerialized, $equipmentInstanceForm) {

        $viewValues = array(
            'equipmentInstanceIds' => $equipmentInstanceIdsSerialized,
            'form' => $equipmentInstanceForm,
            'equipment' => $equipment
        );
        $view = new ViewModel($viewValues);
        $view->setTemplate('equipment/equipment-instance/edit-many.phtml');
        return $view;
    }

    public function editManyAction() {
        $equipmentId = $this->getEvent()->getRouteMatch()->getParam('id', false);
        $equipment = $this->getEquipmentService()->checkEquipmentExists($equipmentId);
        if ($equipment) {
            $this->setBreadcrumbForTaxonomy($equipment->getFirstEquipmentTaxonomy());

            $form = $this->getEquipmentInstanceEditManyForm();

            $request = $this->getRequest();

            if ($request->isPost()) {
                $post = $request->getPost();
                $equipmentInstanceIdsSerialized = serialize($post->id);
                $form->get('equipment-instance')->get('listEquipmentInstances')->setValue($equipmentInstanceIdsSerialized);

                return $this->displayEditManyForm($equipment, $equipmentInstanceIdsSerialized, $form);
            }
        }
        $this->sendFlashMessage("The equipment doesn't exist ", "error");
        return $this->redirectToEquipmentInstance('index', $equipmentId);
    }

    public function advancedSearchAction() {
        $formFactory = $this->getFormFactory('Equipment');
        $application = $this->params()->fromRoute('application');
        $jsonEquipments = $this->getEquipmentService()->getEquipmentJson($this->getApplicationName());

        return new ViewModel(array(
            'searchAdvancedForm' => $formFactory->createAdvancedSearchForInstancesForm($this->getObjectManager(), $application),
            'controlSearchAdvancedForm' => $formFactory->createAdvancedSearchForInstanceControlForm($this->getObjectManager(), $application),
            'jsonEquipments' => $jsonEquipments,
            'removeOwner' => $this->getUserService()->getUser($this->getCurrenUser()->getUserId())->getOrganizationRestrictionEnabled()
        ));
    }

    public function doControlSearchAction()
    {
        $request = $this->getRequest();
        $query = $request->getQuery();
        $search = $query->equipment_control;

        if ($search['periodType'] === 'future') {
            return $this->doSearchAction();
        }

        $applicationName = strtoupper($this->params()->fromRoute('application'));
        $page = $this->getNavigationPage("equipment-instance-control-search");
        $page->setLabel($applicationName);

        $instancesControls = array();
        $searchParams = array();
        $request = $this->getRequest();

        if ($request->isGet()) {
            $searchParams = $this->removeKeysWithoutValue($search);

            try{
                $instancesControls = $this->getEquipmentInstanceService()->getInstancesControlSearch(
                    AdvancedSearchHelper::buildParametersForInstanceControlSearch($search)
                );
            } catch(\Exception $ex) {
                $instancesControls = array();
                $this->sendTranslatedFlashMessage($ex->getMessage(), 'default');
            }
        }

        $view = new ViewModel(array(
                'instancesControls' => $instancesControls,
                'controlType' => $searchParams['controlType'],
                'searchParams' => $searchParams
            )
        );
        $view->setTemplate('equipment/equipment-instance/instance-controls.phtml');
        return $view;
    }

    public function doSearchAction() {
        $applicationName = strtoupper($this->params()->fromRoute('application'));
        $page = $this->getNavigationPage("equipment-instance-search");
        $page->setLabel($applicationName);

        $taxonomyService = $this->getEquipmentTaxonomyService();
        $request = $this->getRequest();
        $categoryId = $this->params()->fromRoute('category', null);
        $currentCategory = ($categoryId) ? $taxonomyService->findById($categoryId) : null;

        $equipmentInstances = array();

        $this->setBreadcrumbForTaxonomy($currentCategory);

        $search = array();
        if ($request->isGet()) {
            $query = $request->getQuery();
            $search = $query->equipment_instance;
            if (!$search) {
                $search = $query->equipment_control;
            }

            try{
                $owner = $this->getOrganizationIfRestrictionIsEnabled();
                if($owner != null)
                    $search['owner'] = $owner->getOrganizationId();

                $equipmentInstances = $this->getEquipmentInstanceService()->getEquipmentInstancesSearch(
                    AdvancedSearchHelper::buildParametersForEquipmentInstanceSearch($search)
                );
            } catch(\Exception $ex) {
                $equipmentInstances = array();
                $this->sendTranslatedFlashMessage($ex->getMessage(), 'default');
            }
        }

        $searchParams = $this->removeKeysWithoutValue($search);

        $view = new ViewModel(
            array(
                'equipmentName' => $this->getTranslator()->translate('Search results'),
                'equipmentInstances' => $equipmentInstances,
                'equipmentId' => null,
                'equipmentIntervalDays' => null,
                'advancedSearch' => true,
                'controller' => $this->getControllerName(),
                'searchParams' => $searchParams,
                'shouldDisplayVisualControlFields' => array_key_exists(SearchValues::CONTROL_TYPE_INDEX, $search)
                    && $search[SearchValues::CONTROL_TYPE_INDEX] === SearchValues::CONTROL_TYPE_VISUAL,
            )
        );

        $view->setTemplate('equipment/equipment-instance/index.phtml');

        return $view;
    }

    private function removeKeysWithoutValue($array) {
        $newArray = array();
        foreach ($array as $key => $value) {
            if (!empty($value)) {
                $newArray[$key] = $value;
            }
        }
        return $newArray;
    }

    public function exportSearchAction() {
        $searchParamsFromRoute = $this->params()->fromRoute();
        $searchParams = AdvancedSearchHelper::buildParametersForEquipmentInstanceSearch($searchParamsFromRoute);
        $equipmentInstances = $this->getEquipmentInstanceService()
            ->getEquipmentInstancesSearch($searchParams, false);
        $reportService = $this->getEquipmentInstanceReportService();
        $report = $reportService->createSearchResultReportTable($equipmentInstances);
        $this->exportReport($report, $this->getExportTypeFromPost());
        return $this->redirectToReferer();
    }

    public function exportControlSearchAction() {
        $searchParamsFromRoute = $this->params()->fromRoute();
        $searchParams = AdvancedSearchHelper::buildParametersForInstanceControlSearch($searchParamsFromRoute);
        $instanceControls = $this->getEquipmentInstanceService()
            ->getInstancesControlSearch($searchParams, false);
        $reportService = $this->getEquipmentInstanceReportService();
        $report = $reportService->createControlSearchResultReportTable($instanceControls);
        $this->exportReport($report, $this->getExportTypeFromPost());
        return $this->redirectToReferer();
    }

    /**
     * @return EquipmentTaxonomyService
     */
    private function getEquipmentTaxonomyService() {
        return $this->getService('Equipment\Service\EquipmentTaxonomyService');
    }

    /**
     * @return \Application\Service\ApplicationFeatureService
     */
    public function getApplicationFeatureService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationFeatureService');
    }

    private function saveEquipmentInstance($form, $requestPost, $isNew = true) {
        $form->setData($requestPost);
        if ($form->isValid()) {
            $service = $this->getEquipmentInstanceService();
            $currentUserId = $this->getCurrenUser()->getId();
            $equipmentInstance = $form->getObject();

            $this->filterInstanceForRoles($equipmentInstance, $isNew);

            if ($this->hasDuplicateValues($equipmentInstance, $message)) {
                $this->handleIfSomethingWrong($message);
                return null;
            }

            $resultId = $service->saveEquipmentInstance($equipmentInstance, $currentUserId);
            if ($resultId > 0) {
                $this->updateMinDatesRelated(array($resultId));
                $message = $this->getTranslator()->translate(
                    'The equipment instance has been successfully saved.');
                $this->flashMessenger()
                    ->setNamespace("success")->addMessage($message);
            }
            else {
                $message = $this->getTranslator()->translate(
                    'The equipment instance could not be saved. Try again later.');
                $this->handleIfSomethingWrong($message);
            }
            $equipmentId = $equipmentInstance->getEquipment()->getEquipmentId();
            return $equipmentId;
        }
        else {
            return null;
        }
    }

    private function hasDuplicateValues($equipmentInstance, &$message) {
        $service = $this->getEquipmentInstanceService();

        $config = $this->serviceLocator->get('Config');

        if($config['vidum']['serial_number_unique']) {
            if ($service->serialNumberExists($equipmentInstance->getSerialNumber(),
                $equipmentInstance->getEquipmentInstanceId())
            ) {
                $message = $this->getTranslator()->translate('The serial number already exists.');
                return true;
            }
        }

        if($config['vidum']['check_reg_number_by_equipment']) {
            $equipmentId = $equipmentInstance->getEquipment()->getEquipmentId();
            if ($service->regNumberExists($equipmentInstance->getRegNumber(), $equipmentInstance->getEquipmentInstanceId(), $equipmentId)) {
                $message = $this->getTranslator()->translate('The reg. number already exists.');
                return true;
            }
        }
        return false;
    }

    private function updateMinDatesRelated($ids) {
        $idsToUpdate = array();
        if (!is_array($ids)) $idsToUpdate = explode(',', $ids);
        else    $idsToUpdate = $ids;
        $this->getEquipmentInstanceControlDateService()->updateDataByIds($idsToUpdate);
    }

    private function getEquipmentSubinstanceForm($equipmentInstance, $availableEquipmentInstances) {
        $formFactory = $this->getFormFactory('Equipment');
        $form = $formFactory->createEquipmentSubinstanceForm($availableEquipmentInstances);

        $form->bind($equipmentInstance);

        return $form;
    }

    protected function getEquipmentInstanceForm($equipmentInstance) {
        $formFactory = $this->getFormFactory('Equipment');
        $form = $formFactory->createEquipmentInstanceForm();
        $this->addRestrictionsToEquipmentInstanceForm($form);
        $form->bind($equipmentInstance);
        return $form;
    }

    protected function setRegisterNumberForNewForm($form) {
        $newRegNumer = $this->getEquipmentInstanceService()->getNewRegNumberByApplication($this->getApplicationName());
        $form->get('equipment-instance')->get('regNumber')->setValue($newRegNumer);
        return $form;
    }

    protected function addRestrictionsToEquipmentInstanceForm($equipmentInstanceForm) {

        $fieldset = $equipmentInstanceForm->get('equipment-instance');

        if($this->userIsRole('controller'))
            $fieldset->remove('status');
    }

    protected function filterInstanceForRoles($instance, $isNew = true) {
        if($this->userIsRole('controller') && $isNew)
            $instance->setStatus(\Equipment\Entity\EquipmentInstance::STATUS_ACTIVE);
    }

    protected function userIsRole($roleId) {
        $user = $this->getCurrenUser();
        foreach($user->getRoles() as $role) {
            if($role->getRoleId() == $roleId)
                return true;
        }

        return false;
    }

    private function getEquipmentInstanceEditManyForm($equipmentInstance = null) {
        $config = $this->serviceLocator->get('Config');

        $formFactory = $this->getFormFactory('Equipment');
        $formFactory->setTranslator($this->getTranslator());
        $form = $formFactory->createEquipmentInstanceEditManyForm($config['vidum']['check_reg_number_by_equipment']);
        if (!empty($equipmentInstance)) {
            $form->bind($equipmentInstance);
        }

        return $form;
    }

    private function handleIfSomethingWrong($errorMessage = '') {

        if (empty($errorMessage)) {
            $errorMessage = $this->getTranslator()->translate(
                "Action could not be completed");
        }
        $this->flashMessenger()
            ->setNamespace('error')->addMessage($errorMessage);
    }

    private function validateAddSubinstanceForm($form, $request, $equipmentInstanceId) {
        $form->setData($request->getPost());
        $equipmentInstanceService = $this->getEquipmentInstanceService();
        if ($form->isValid()) {
            $postChildId = $form->get('equipment_subinstance')->get('childId')->getValue();
            $translator = $this->getTranslator();

            if (!empty($postChildId)) {
                foreach ($postChildId as $childId) {
                    $equipmentInstanceService->updateEquipmentSubinstance((int)$childId, $equipmentInstanceId);
                }
                $namespace = 'success';
                $message = $translator->translate('Equipment Subinstance was added successfully');
            }
            else {
                $namespace = 'error';
                $message = $translator->translate('no subinstance added');
            }
            $this->flashMessenger()
                ->setNamespace($namespace)
                ->addMessage($message);

            return true;
        }
        return false;
    }

    public function addSubinstanceAction() {
        $this->layout('layout/iframe');
        $request = $this->getRequest();
        $equipmentInstanceId = (int)$this->params()->fromRoute('id', 0);
        $equipmentInstanceService = $this->getEquipmentInstanceService();
        $equipmentInstance = $equipmentInstanceService->getEquipmentInstance($equipmentInstanceId);

        if (empty($equipmentInstance)) {
            $this->sendFlashMessage("EquipmentInstance doesn't exist" . "error");
            return $this->redirectToEquipment();
        }

        $availableEquipmentInstances = $equipmentInstanceService
            ->getAvailableEquipmentInstance($equipmentInstance, $this->getOrganizationIfRestrictionIsEnabled());
        $form = $this->getEquipmentSubinstanceForm($equipmentInstance, $availableEquipmentInstances);
        if ($request->isPost()) {
            $this->validateAddSubinstanceForm($form, $request, $equipmentInstanceId);
            $this->updateMinDatesRelated(array($equipmentInstanceId));
            return array();
        }
        return array('form' => $form);
    }

    /**
     * Checks if the user has the "restriction to filter instances by organization" enabled
     * @return \Application\Entity\Organization|null
     */
    private function getOrganizationIfRestrictionIsEnabled()
    {
        $currentUser = $this->getUserService()->getUser($this->getCurrenUser()->getUserId());
        $organizationToFilter = null;
        if($currentUser->getOrganizationRestrictionEnabled())
            $organizationToFilter = $currentUser->getOrganizationId();

        return $organizationToFilter;
    }

    /**
     * @return \Application\Service\UserService
     */
    private function getUserService() {
        return $this->getServiceLocator()->get(
            'Application\Service\UserService');
    }

    /**
     * @return EquipmentInstanceService
     */
    protected function getEquipmentInstanceService() {
        return $this->getService('Equipment\Service\EquipmentInstanceService');
    }

    /**
     * @return EquipmentInstanceContainerService
     */
    protected function getEquipmentInstanceContainerService() {
        return $this->getService('Equipment\Service\EquipmentInstanceContainerService');
    }


    private function getEquipmentInstanceControlDateService() {
        return $this->getService('Equipment\Service\EquipmentInstanceControlDateService');
    }

    /**
     * @return EquipmentInstanceReportService
     */
    private function getEquipmentInstanceReportService() {
        return $this->getServiceLocator()->get(
            'Equipment\Service\EquipmentInstanceReportService');
    }

    /**
     * @return PeriodicControlService
     */
    private function getPeriodicControlService() {
        return $this->getService('Equipment\Service\PeriodicControlService');
    }

    /**
     * @return VisualControlService
     */
    private function getVisualControlService() {
        return $this->getService('Equipment\Service\VisualControlService');
    }

    /**
     * @return EquipmentService
     */
    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    private function getCheckoutService() {
        return $this->getServiceLocator()->get(
            'Equipment\Service\CheckoutService');
    }

    /**
     * Doctrine entity manager
     * @var object
     */
    private function getObjectManager() {
        return $this->objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    private function redirectToEquipmentInstance($action, $id) {

        return $this->redirect()->toRoute('base/wildcard', array(
            'application' => $this->params()->fromRoute('application'),
            'controller' => $this->getControllerName(),
            'action' => $action,
            'id' => $id
        ));
    }

    private function redirectToEquipment() {
        return $this->redirect()->toRoute('base/wildcard', array(
            'application' => $this->params()->fromRoute('application'),
            'controller' => 'index',
            'action' => 'index',
        ));
    }

}
