<?php

namespace Application\Controller;

use Application\Entity\StandardMessages;
use Application\Utility\FlashMessage;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Utility\ServiceMessageToFlashMessageConverter;
use Application\Utility\ServiceMessage;
use \Acl\ORM\AclEntityManager;

abstract class AbstractBaseController extends AbstractActionController {

    protected $services = array();

    /**
     * Get the default Translator service
     *
     * @return Translator service
     */
    protected function getTranslator() {
        return $this->getServiceLocator()
            ->get('translator');
    }

    protected function translate($text) {
        return $this->getTranslator()->translate($text);
    }

    /**
     * Get a service by its Service ClassName
     *
     * @param String $serviceName
     * @return type
     */
    protected function getRegisteredInstance($serviceName) {
        return $this->getServiceLocator()->get($serviceName);
    }

    protected function getService($serviceName) {
        if (!array_key_exists($serviceName, $this->services)) {
            $service = $this->getServiceLocator()->get($serviceName);
            if ($service instanceof \Acl\Service\AbstractService) {
                $service->setApplication($this->getApplicationName());
            }

            $this->services[$serviceName] = $service;
        }

        return $this->services[$serviceName];
    }

    /**
     * Get the registered Form Factory
     *
     * @param String $moduleName
     * @param String $factoryPrefix
     * @return RegisteredInstance Instance of FormFactory
     */
    protected function getFormFactory($moduleName = "Application", $factoryPrefix = "") {
        $serviceClassName = "\\" . $moduleName . "\\Form\\{$factoryPrefix}FormFactory";
        $formFactory = $this->getRegisteredInstance($serviceClassName);
        $objectManager = $formFactory->getObjectManager();
        if ($objectManager instanceof \Acl\ORM\AclEntityManager) {
            $objectManager->setApplication($this->getApplicationName());
        }

        return $formFactory;
    }

    protected function addFlashMessage($namespace, $message) {
        $translator = $this->getTranslator();

        $this->flashMessenger()
            ->setNamespace($namespace)
            ->addMessage($translator->translate($message));
    }

    /**
     * Add a message to Flash messenger
     *
     * @param String $message
     * @param String $nameSpace
     * @param boolean $isTranslated Default is: false
     */
    protected function sendFlashMessage($message, $namespace = 'success', $isTranslated = false) {
        if ($isTranslated) {
            $messageToAdd = $message;
        }
        else {
            $messageToAdd = $this->getTranslator()->translate($message);
        }
        $this->flashMessenger()
            ->setNamespace($namespace)
            ->addMessage($messageToAdd);
    }

    public function sendTranslatedFlashMessage($message, $namespace = 'success') {
        $this->sendFlashMessage($message, $namespace, true);
    }

    public function sendFlashMessageFrom(FlashMessage $flashMessage) {
        $this->sendTranslatedFlashMessage($flashMessage->getMessage(), $flashMessage->getNamespace());
    }

    protected function displayTranslatedServiceMessage(ServiceMessage $serviceMessage) {
        $serviceMessageToFlashMessageConverter = new ServiceMessageToFlashMessageConverter();
        $flashMessage = $serviceMessageToFlashMessageConverter->convert($serviceMessage);
        $this->sendFlashMessageFrom($flashMessage, true);
    }

    protected function redirectToReferer() {
        $request = $this->getRequest();
        $httpReferer = $request->getServer('HTTP_REFERER');
        if ($httpReferer) {
            return $this->redirect()->toUrl($httpReferer);
        }
        else {
            return $this->redirectToPath("index");
        }
    }

    /**
     * Return a redirection
     *
     * @param String $controller
     * @param String $action
     * @return redirect
     */
    protected function redirectToPath($controller, $action = 'index', $params = array()) {
        return $this->redirect()->toRoute('base/wildcard', array_merge($params, array(
                'application' => $this->params()->fromRoute('application'),
                'controller' => $controller,
                'action' => $action
            ))
        );
    }

    public function getNavigationPage($pageId, $property = "id") {
        $navigation = $this->getServiceLocator()->get('Navigation');
        return $navigation->findBy($property, $pageId);
    }

    /**
     * Get generic title for admin
     *
     * @return string
     */
    protected function getAdminTitle() {
        $adminTitle = $this->getTranslator()->translate('Admin') . ": ";
        return $adminTitle;
    }

    /**
     * Shows a generic error message
     */
    public function displayGenericErrorMessage() {
        $this->sendFlashMessage("Action could not be completed", "error");
    }

    protected function getConfigService() {
        return $this->getService('Application\Service\ConfigService');
    }

    protected function getConfigArray() {
        return $this->getRegisteredInstance('Vidum\Config');
    }

    protected function getApplicationName() {
        return $this->params()->fromRoute('application');
    }

    protected function getApplication() {
        $applicationKey = $this->getApplicationName();

        return $this->getService('Application\Service\ApplicationService')->getApplication($applicationKey);
    }

    /**
     * Get current logged in user
     *
     * @return \Application\Entity\User $user
     */
    protected function getCurrenUser() {
        return $this->zfcUserAuthentication()->getIdentity();
    }

    protected function manageException($exception) {
        $this->sendFlashMessage($exception->getMessage(), 'error', true);
    }

    protected function exportReport($report, $type) {
        $reportExporterFactory = new \Application\Factory\Service\ReportExporterFactory();
        $reportExporter = $reportExporterFactory->createReportExporter($type);
        $reportExporter->export($report);
        return $this->redirectToReferer();
    }

    protected function setBreadcrumbForApplication() {
        $applicationName = strtoupper($this->params()->fromRoute('application'));
        $page = $this->getNavigationPage("equipment-home");
        $page->setLabel($this->translate($applicationName));
        return $page;
    }

    protected function setBreadcrumbTaxonomyLevels($applicationName, $page, $taxonomy) {
        $parentPage = $page->getParent();
        $newPage = clone $page;

        $parentPage->addPage($newPage);
        $taxonomyParent = $taxonomy->getParent();
        $newPage->setLabel($taxonomyParent->getName());
        $newPage->setParams(array(
                'application' => $applicationName,
                'category' => $taxonomyParent->getEquipmentTaxonomyId()
            )
        );
        $page->setParent($newPage);
        return $newPage;
    }

    protected function setBreadcrumbForTaxonomy($category) {
        if ($category) {
            $applicationName = $this->params()->fromRoute('application');
            $page = $this->getNavigationPage("equipment-category");
            $taxonomyLevel = $category->getLevel();
            for ($index = 1; $index < $taxonomyLevel; $index++) {
                if ($index == 1) {
                    $parent = $category;
                    $newPage = $page;
                }
                else {
                    $parent = $parent->getParent();
                }
                $newPage = $this->setBreadcrumbTaxonomyLevels($applicationName, $newPage, $parent);
            }

            $page->setLabel($category->getName());
            $page->setParams(array(
                    'application' => $applicationName,
                    'category' => $category->getEquipmentTaxonomyId()
                )
            );
        }
    }

    protected function setBreadcrumbForEquipment($equipment) {
        if ($equipment) {
            $applicationName = $this->params()->fromRoute('application');
            $page = $this->getNavigationPage("equipment-detail");
            $page->setLabel($equipment->getTitle());
            $page->setParams(
                array(
                    'application' => $applicationName,
                    'id' => $equipment->getEquipmentId()
                )
            );
        }
    }

    /**
     *
     * @param \Documentation\Entity\Page $pageEntity
     */
    protected function setBreadcrumbForCategoryPage($pageEntity, $breadcrumbId = "category-page") {
        $page = $this->getNavigationPage($breadcrumbId);
        $parentPage = $this->setBreadcrumbForApplication();
        $taxonomy = $pageEntity->getCategory();
        if ($taxonomy) {
            $this->setBreadcrumbForTaxonomy($taxonomy);
        }
        else {
            $page->setParent($parentPage);
        }

        $page->setLabel($pageEntity->getName());

        return $page;
    }

    protected function setBreadcrumbForEquipmentFeature($equipment) {
        $this->setBreadcrumbForApplication();
        $this->setBreadcrumbForTaxonomy($equipment->getFirstEquipmentTaxonomy());
        $this->setBreadcrumbForEquipment($equipment);
    }

    public function setBreadcrumbForFeatureActions($equipment, $controllerName) {
        $this->setBreadcrumbForEquipmentFeature($equipment);
        $featurePage = $this->getNavigationPage($controllerName, 'controller');
        if ($featurePage) {
            $applicationName = $this->params()->fromRoute('application');
            $featurePage->setParams(
                array(
                    'application' => $applicationName,
                    'id' => $equipment->getEquipmentId()
                )
            );
        }
    }

    /**
     * @return StandardMessages
     */
    protected function getStandardMessages() {
        return new StandardMessages($this->getTranslator());
    }


    // Doctrine doesn't automatically clear removal of multiple selections,
    // as the posted values don't include these fields.
    // https://github.com/doctrine/DoctrineModule/issues/215
    protected function handleEmptyMultiselect($post, $formIndex, $valueIndex) {
        if (!array_key_exists($valueIndex, $post[$formIndex])) {
            $post[$formIndex][$valueIndex] = array();
        }
        return $post;
    }
}
