<?php

namespace Equipment\Controller;

use Application\Constants\StatusConstants;
use Application\Controller\Helper\DeactivationHelper;
use Application\Service\ApplicationFeatureService;
use Equipment\Entity\InstanceExpirationFieldTypes;
use Equipment\Entity\Equipment;
use Equipment\Service\EquipmentInstanceService;
use Equipment\Service\EquipmentService;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Application\Controller\AbstractBaseController;
use Equipment\Controller\Helper\ConfigFieldsHelper;

/**
 * This controller is related to equipment entity
 *
 */
class EquipmentController extends AbstractBaseController {

    protected $objectManager;

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
            if (in_array($actionName, array('detail', 'attachment-index', 'index', 'do-search'))) {
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

    /**
     * Doctrine entity manager
     * @var object
     */
    private function getObjectManager() {
        return $this->objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    private function getUserService() {
        return $this->getServiceLocator()->get('Application\Service\UserService');
    }

    /**
     * @return EquipmentService
     */
    protected function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    /**
     * @return EquipmentInstanceService
     */
    private function getEquipmentInstanceService() {
        return $this->getService('Equipment\Service\EquipmentInstanceService');
    }

    private function getEquipmentInstanceControlDateService() {
        return $this->getService('Equipment\Service\EquipmentInstanceControlDateService');
    }

    protected function getNewEquipment() {
        $equipment = new \Equipment\Entity\Equipment();
        return $equipment;
    }

    protected function getEntityResource() {
        return 'Equipment\Entity\Equipment';
    }

    private function getEquipment($id) {
        $equipment = $this->getEquipmentService()->getEquipment($id);
        return $equipment;
    }

    public function addAction() {
        $this->layout('layout/iframe');

        $categoryId = $this->params()->fromRoute('category', 0);
        $equipment = $this->getNewEquipment();
        $equipmentForm = $this->getEquipmentForm($equipment);
        $action = 'add';
        if ($categoryId) {
            $equipmentForm->get('equipment')->get('equipmentTaxonomy')->setValue($categoryId);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            return $this->storePostData($request, $action, $equipmentForm);
        }
        else {
            return $this->displayForm($equipmentForm, $equipment);
        }
    }

    private function prepareFieldsByPost($post)
    {
        if(isset($post['equipment']['sap']) && $post['equipment']['sap'] == '________')
            $post['equipment']['sap'] = '';
    }

    private function storePostData($request, $action, $equipmentForm, $equipmentId = 0) {
        $requestPost = $request->getPost()->toArray();

        $requestPost = $this->handleEmptyMultiselect($requestPost, 'equipment', 'featureOverrides');
        $requestPost = $this->handleEmptyMultiselect($requestPost, 'equipment', 'canBeUsedWith');
        $requestPost = $this->handleEmptyMultiselect($requestPost, 'equipment', 'hasToBeUsedWith');

        $post = array_merge_recursive(
            $requestPost, $request->getFiles()->toArray()
        );

        $this->prepareFieldsByPost($post);

        if($action == 'update' && isset($post['equipment']['instanceType']))
            unset($post['equipment']['instanceType']);

        $equipmentForm->setData($post);
        $equipment = $equipmentForm->getObject();

        $application = $this->params()->fromRoute('application');
        if($action == "add" && $application == "ladoc"){
            $equipment->setInstanceType(Equipment::INSTANCE_TYPE_STANDARD);
        }

        if ($equipmentForm->isValid()) {
            $this->updateImage($equipment, $post);

            $equipment->setUser(
                $this->getUserService()->getUser($this->zfcUserAuthentication()->getIdentity()->getId())
            );

            $this->prepareEquipmentForSave($equipment);
            $equipmentService = $this->getEquipmentService();
            $equipmentService->persistEquipment($equipment, $action);
            if ($equipment->getStatus() === StatusConstants::INACTIVE) {
                $this->deactivateInstances($equipment->getEquipmentId());
            }
            $lastEquipmentId = $equipment->getEquipmentId();
            $equipmentService->saveEquipmentJson($action, $post['equipment']['title'], $action == 'add' ? $lastEquipmentId : $equipment->getEquipmentId());

            $message = $this->getTranslator()->translate('Equipment Information was saved succesfully.');
            $this->flashMessenger()->setNamespace('success')->addMessage($message);

            $view = new ViewModel(array("messageError" => 'success'));
            $view->setTemplate('equipment/equipment/edit.phtml');
            return $view;
        }
        else {
            return $this->displayForm($equipmentForm, $equipment, $equipmentId);
        }
    }

    protected function prepareEquipmentForSave($equipment) {
    }

    private function updateImage($equipment, $postData) {
        $featuredImageFromEquipment = $equipment->getFeatureImage();
        $featuredImageFromPost = $postData['equipment']['feature_image_file'];
        $equipmentService = $this->getEquipmentService();
        if ($postData['remove_image']) {
            $equipmentService->deleteImage($featuredImageFromEquipment);
            $equipment->setFeatureImage(null);
        }
        else {
            $newImageName = $equipmentService->resizeImage($featuredImageFromPost, 500, $featuredImageFromEquipment);
            if ($newImageName) {
                $equipment->setFeatureImage($newImageName);
            }
        }
    }

    private function displayForm($equipmentForm, Equipment $equipment) {
        $viewValues = array(
            'form' => $equipmentForm,
            'featureImage' => $equipment->getFeatureImage() !== null ? $equipment->getFeatureImage() : null
        );

        if($equipmentForm->get('equipment')->has('controlIntervalByDays')) {
            $controlIntervalDay = $equipmentForm->get('equipment')->get('controlIntervalByDays')->getValue();
            if ($controlIntervalDay == '' && $controlIntervalDay !== '0') {
                $equipmentForm->get('equipment')->get('controlIntervalByDays')->setValue('365');
            }
        }

        $view = new ViewModel($viewValues);
        $view->setTemplate('equipment/equipment/edit.phtml');
        return $view;
    }

    protected function getEquipmentForm(Equipment $equipment) {
        $formFactory = $this->getFormFactory('Equipment');
        $categoryValues = $this->getEquipmentTaxonomyService()->getAvailableEquipmentTaxonomy(0);
        $fieldsToShow = $this->getFieldsForEquipmentForm();
        $equipmentId = $equipment->getEquipmentId() ? $equipment->getEquipmentId() : 0;
        $application = $this->params()->fromRoute('application');
        $equipmentForm = $formFactory->createEquipmentForm($categoryValues, $equipmentId, $fieldsToShow, $application);
        $equipmentForm->bind($equipment);
        return $equipmentForm;
    }

    /**
     * This method is for edit and create equipments
     *
     * return array
     */
    public function editAction() {
        $this->layout('layout/iframe');

        $categoryId = $this->params()->fromRoute('category', 0);
        $equipmentId = $this->params()->fromRoute('id', 0);

        $equipment = $this->getEquipment($equipmentId);

        if (empty($equipment)) {
            $message = $this->getTranslator()->translate('Equipment does not exist');
            $messageStyle = 'error';
            $this->flashMessenger()->setNamespace($messageStyle)->addMessage($message);
            return $this->redirectToEquipment('index');
        }

        $equipmentForm = $this->getEquipmentForm($equipment);

        if ($categoryId) {
            $equipmentForm->get('equipment')->get('equipmentTaxonomy')->setValue($categoryId);
        }
        $action = 'update';

        $request = $this->getRequest();

        if ($request->isPost()) {
            return $this->storePostData($request, $action, $equipmentForm, $equipmentId);
        }
        else {

            return $this->displayForm($equipmentForm, $equipment);
        }
    }

    public function doSearchAction() {
        $applicationName = strtoupper($this->params()->fromRoute('application'));
        $page = $this->getNavigationPage("equipment-search");
        $page->setLabel($applicationName);

        $taxonomyService = $this->getEquipmentTaxonomyService();
        $request = $this->getRequest();
        $categoryId = $this->params()->fromRoute('category', null);
        $currentCategory = ($categoryId) ? $taxonomyService->findById($categoryId) : null;

        $this->setBreadcrumbForTaxonomy($currentCategory);

        $categories = $equipments = $pages = null;

        if ($request->isGet()) {
            $query = $request->getQuery();
            $search = $query->equipment;

            $categoryToSearch = isset($search['category']) ? $search['category'] : null;
            $supplierToSearch = isset($search['supplier']) ? $search['supplier'] : null;

            $equipments = $this->getEquipmentService()->getEquipmentSearch(
                array(
                    'taxonomies' => array('category' => $categoryToSearch),
                    'attributes_equal' => array(
                        'nsn' => $search['nsn'],
                        'sap' => $search['sap']),
                    'attributes' => array('vendor' => $supplierToSearch, 'title' => '%' . $query->keyWord . '%')
                )
            );
            if (count($equipments) == 1) {
                $singleEquipment = $equipments[0];
                return $this->redirectToEquipment('detail', $singleEquipment->getEquipmentId());
            }
        }

        $applicationName = $this->params()->fromRoute('application');
        $currentApplication = $this->getApplicationService()->getApplication($this->params()->fromRoute('application'));

        $view = new ViewModel(
            array(
                'categoryId' => $categoryId,
                'categories' => $categories,
                'equipment' => $equipments,
                'pages' => $pages,
                'title' => isset($currentCategory) ? $currentCategory->getName() : null,
                'totalExpiredControlDate' => 0,
                'totalExpiredGuarantee' => 0,
                'totalExpiredLifeTime' => 0,
                'isRootPage' => false,
                'currentApplication' => $currentApplication,
                'applicationName' => strtoupper($applicationName),
                'isSearch' => true,
            )
        );

        $view->setTemplate('equipment/equipment/index.phtml');

        return $view;
    }

    public function indexAction() {
        $this->setBreadcrumbForApplication();
        $taxonomyService = $this->getEquipmentTaxonomyService();
        $categoryId = $this->params()->fromRoute('category', null);
        $currentCategory = ($categoryId) ? $taxonomyService->findById($categoryId) : null;

        $this->setBreadcrumbForTaxonomy($currentCategory);

        $pages = $this->getPageService()->listPagesByCategory($categoryId);
        $categories = $taxonomyService->fetchEquipmentTaxonomy(
            array('type' => 'category', 'parent' => $categoryId, 'status' => 'active'));
        $equipment = ($currentCategory) ? $currentCategory->getActiveEquipments() : null;

        $applicationName = $this->params()->fromRoute('application');
        $currentApplication = $this->getApplicationService()->getApplication($applicationName);

        if($this->getApplicationFeatureService()->getApplicationFeatureByKey($applicationName, 'instances')) {
            $equipmentInstanceService = $this->getEquipmentInstanceService();
            $this->getEquipmentInstanceControlDateService()->checkMinDates();

            $expirationFields = array(
                InstanceExpirationFieldTypes::PERIODIC_CONTROL,
                InstanceExpirationFieldTypes::GUARANTEE,
                InstanceExpirationFieldTypes::TECHNICAL_LIFETIME
            );
            $expirationCounts = $equipmentInstanceService->getExpiredCounts($expirationFields, 'category', $categoryId);
            $totalExpiredByControlDate = $expirationCounts[InstanceExpirationFieldTypes::PERIODIC_CONTROL];
            $totalExpiredByGuarantee = $expirationCounts[InstanceExpirationFieldTypes::GUARANTEE];
            $totalExpiredByLifeTime = $expirationCounts[InstanceExpirationFieldTypes::TECHNICAL_LIFETIME];
        } else {
            $totalExpiredByControlDate = 0;
            $totalExpiredByGuarantee = 0;
            $totalExpiredByLifeTime = 0;
        }

        return new ViewModel(
            array(
                'categoryId' => $categoryId,
                'categories' => $categories,
                'equipment' => $equipment,
                'pages' => $pages,
                'title' => isset($currentCategory) ? $currentCategory->getName() : null,
                'isRootPage' => $categoryId === null,
                'expiredInstanceTotals' => array(
                    'totalExpiredByControlDate' => $totalExpiredByControlDate,
                    'totalExpiredByGuarantee' => $totalExpiredByGuarantee,
                    'totalExpiredByLifeTime' => $totalExpiredByLifeTime,
                    'categoryId' => $categoryId
                ),
                'currentApplication' => $currentApplication,
                'applicationName' => strtoupper($applicationName),
                'isSearch' => false,
            )
        );
    }

    public function adminIndexAction() {
        $equipments = $this->getEquipmentService()->listEquipments();
        $taxonomies = $this->getEquipmentService()->taxonomiesBelongEquipments($equipments);

        return new ViewModel(
            array(
                'equipments' => $equipments,
                'taxonomies' => $taxonomies,
                'title' => 'Admin: ' . $this->getTranslator()->translate('Equipment'),
                'entityResource' => $this->getEntityResource()
            )
        );
    }

    public function deactivateAction() {
        $post = $this->getRequest()->getPost();
        $id = $post['id'];
        $deactivationHelper = $this->getDeactivationHelper();
        $success = $deactivationHelper->deactivateAction($id);

        if ($success) {
            $this->getEquipmentService()->deleteFromJson($id);
            $this->deactivateInstances($id);
        }

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function deactivateInstances($id) {
        $equipment = $this->getEquipmentService()->getEquipment($id);
        $instances = $equipment->getInstances();
        $instanceIds = $this->getEquipmentInstanceService()->extractIds($instances);
        $instanceDeactivationHelper = $this->getInstanceDeactivationHelper();
        $instanceDeactivationHelper->deactivateManyAction($instanceIds);
    }

    public function reactivateAction() {
        $post = $this->getRequest()->getPost();
        $id = $post['id'];
        $deactivationHelper = $this->getDeactivationHelper();
        $success = $deactivationHelper->activateAction($id);

        if ($success) {
            $service = $this->getEquipmentService();
            $equipment = $service->getEquipment($id);
            $service->saveEquipmentJson('add', $equipment->getTitle(), $id);
        }

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function deactivateManyAction() {
        $post = $this->getRequest()->getPost();
        $ids = explode(',', $post['deactivate_ids']);
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateManyAction($ids);
        $service = $this->getEquipmentService();
        $instanceService = $this->getEquipmentInstanceService();
        $instanceDeactivationHelper = $this->getInstanceDeactivationHelper();
        foreach ($ids as $id) {
            $equipment = $service->findById($id);
            $instanceIds = $instanceService->extractIds($equipment->getInstances());
            $instanceDeactivationHelper->deactivateManyAction($instanceIds);
        }
        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function detailAction() {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $application = $this->params()->fromRoute('application');


        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        $translator = $this->getTranslator();
        if ($equipment === null) {
            $this->flashMessenger()
                ->setNamespace('error')
                ->addMessage($translator->translate("Equipment doesn't exist"));
            return $this->redirectToEquipment('index');
        }
        $features = $this->getApplicationFeatureService()->getApplicationFeatures($application,
            $equipment->getFeatureOverrides());

        if (count($features) == 1) {
            $routeToFeature = $features[0]->getRoute();
            return $this->redirectToPath($routeToFeature['controller'], $routeToFeature['action'], array('id' => $equipmentId));
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);

        $equipmentInstanceService = $this->getEquipmentInstanceService();

        $expirationFields = array(
            InstanceExpirationFieldTypes::PERIODIC_CONTROL,
            InstanceExpirationFieldTypes::GUARANTEE,
            InstanceExpirationFieldTypes::TECHNICAL_LIFETIME
        );
        $expirationCounts = $equipmentInstanceService->getExpiredCounts($expirationFields, 'equipment', $equipmentId);
        $totalExpiredByControlDate = $expirationCounts[InstanceExpirationFieldTypes::PERIODIC_CONTROL];
        $totalExpiredByGuarantee = $expirationCounts[InstanceExpirationFieldTypes::GUARANTEE];
        $totalExpiredByLifeTime = $expirationCounts[InstanceExpirationFieldTypes::TECHNICAL_LIFETIME];

        return new ViewModel(array(
            'title' => $equipment->getTitle(),
            'equipmentId' => $equipmentId,
            'features' => $features,
            'instanceType' => $equipment->getInstanceType(),
            'expiredInstanceTotals' => array(
                'totalExpiredByControlDate' => $totalExpiredByControlDate,
                'totalExpiredByGuarantee' => $totalExpiredByGuarantee,
                'totalExpiredByLifeTime' => $totalExpiredByLifeTime,
                'equipmentId' => $equipmentId
            )
        ));
    }

    public function attachmentIndexAction() {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->flashMessenger()
                ->setNamespace('error')
                ->addMessage($this->getTranslator()->translate("Equipment doesn't exist"));
            return $this->redirectToEquipment('index');
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);
        $view = new ViewModel(array(
            'title' => $equipment->getTitle() . ': Attachments ',
            'equipmentId' => $equipmentId
        ));

        $attachmentTable = $this->forward()->dispatch('Controller\EquipmentAttachment'
            , array(
                'action' => 'attachment-table',
                'id' => $equipmentId,
            )
        );
        $view->addChild($attachmentTable, 'attachmentTable');
        return $view;
    }

    public function searchAction() {
        $term = isset($_GET['term']) ? $_GET['term'] : "";
        $jsonEquipments = $this->getEquipmentService()->getEquipmentJson($this->getApplicationName(), $term);
        $equipmentValues = array();
        foreach($jsonEquipments as $jsonEquipment)
            $equipmentValues[] = array(
                'value' => $jsonEquipment->equipment_id,
                'text' => $jsonEquipment->title
            );
        die(json_encode($equipmentValues));
    }

    private function getFieldsForEquipmentForm()
    {
        $application = $this->params()->fromRoute('application');
        $configFieldsHelper = new ConfigFieldsHelper($this->getServiceLocator());
        return $configFieldsHelper->getEquipmentFieldsByApplication($application);
    }

    /**
     * @return ApplicationFeatureService
     */
    public function getApplicationFeatureService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationFeatureService');
    }

    public function getApplicationService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationService');
    }

    /**
     * @return \Equipment\Service\EquipmentTaxonomyService
     */
    protected function getEquipmentTaxonomyService() {
        return $this->getService('Equipment\Service\EquipmentTaxonomyService');
    }

    private function getPageService() {
        return $this->getService('Documentation\Service\PageService');
    }

    public function advancedSearchAction() {
        $formFactory = $this->getFormFactory('Equipment');
        $application = $this->params()->fromRoute('application');
        return new ViewModel(array(
            'application' => $application,
            'searchAdvancedForm' => $formFactory->createAdvancedSearchForm($this->getObjectManager(), $application),
            'jsonEquipments' => $this->getEquipmentService()->getEquipmentJson($this->getApplicationName())
        ));
    }

    private function redirectToEquipment($action, $id = 0) {
        return $this->redirect()->toRoute('base/wildcard', array(
            'application' => $this->params()->fromRoute('application'),
            'controller' => 'equipment',
            'action' => $action,
            'id' => $id
        ));
    }

    /**
     * @return DeactivationHelper
     */
    private function getDeactivationHelper() {
        return new DeactivationHelper($this, $this->getEquipmentService(), $this->getTranslator());
    }

    /**
     * @return DeactivationHelper
     */
    private function getInstanceDeactivationHelper() {
        return new DeactivationHelper($this, $this->getEquipmentInstanceService(), $this->getTranslator());
    }
}
