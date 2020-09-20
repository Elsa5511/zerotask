<?php

namespace Equipment\Controller;

use Application\Controller\AbstractBaseController;
use Application\Entity\StandardMessages;
use Equipment\Controller\Helper\AdvancedSearchHelper;
use Equipment\Entity\Equipment;
use Equipment\Service\EquipmentCompetenceVerifier;
use Equipment\Service\EquipmentInstanceControlDateService;
use Equipment\Service\EquipmentInstanceService;
use Equipment\Service\PeriodicControlPdfExporter;
use Equipment\Service\PeriodicControlService;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\Parameters;

/**
 * This controller is related to Periodic Control for Equipment Instances 
 *  
 */
class PeriodicControlController extends AbstractBaseController {

    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            $actionName = $controller->params()->fromRoute('action');
            $applicationName = $controller->params()->fromRoute('application');
            if (in_array($actionName, array('index'))) {
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

    public function IndexAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        $periodicControl = $this->getPeriodicControlService()->getPeriodicControl($id);

        if (empty($periodicControl)) {
            $this->sendFlashMessage("Periodic Control doesn't exist", 'error');
            return $this->redirectToReferer();
        }

        $equipmentInstance = $periodicControl->getEquipmentInstance();
        $controlTemplate = $this->getEquipmentInstanceService()->getControlTemplate($equipmentInstance);
        $instanceSerialNumber = $equipmentInstance->getSerialNumber();
        $equipment = $equipmentInstance->getEquipment();
        $this->setBreadcrumbForEquipmentDetail($equipment, $equipmentInstance->getEquipmentInstanceId());

        $viewModel = new ViewModel(array(
            'title' => $this->getTranslator()->translate('Periodic control') . ': ' . $instanceSerialNumber,
            'equipmentType' => $equipment,
            'instance' => $equipmentInstance,
            'periodicControl' => $periodicControl,
            'controlTemplate' => $controlTemplate
        ));

        $attachmentTable = $this->forward()->dispatch('Controller\PeriodicControlAttachment',
            array('action' => 'attachment-table', 'id' => $id)
        );
        $viewModel->addChild($attachmentTable, 'attachmentTable');
        return $viewModel;
    }

    /**
     * Add Periodic Control Instance Action
     * 
     * @return $view array|\Zend\View\Model\ViewModel
     */
    public function addAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $this->getAttachmentService()->mergeWithAttachments($request->getPost()->toArray(), $request->getFiles()->toArray());
            $postObj = new Parameters();
            $postObj->fromArray($post);
            $equipment = $this->getEquipment($postObj->equipmentId);

            $this->setBreadcrumbForTaxonomy($equipment->getFirstEquipmentTaxonomy());

            if ($this->userIsCompetentWithEquipment($equipment)) {
                return $this->addPeriodicControl($postObj);
            } else {
                $this->sendFlashMessage('You are not qualified to perform periodic control on this equipment.', 'error');
                //return $this->redirectToEquipmentInstance($equipment->getEquipmentId());
                return $this->redirectToReferer();
            }
        } else {
            $this->displayGenericErrorMessage();
        }
    }

    public function deleteAction() {
        $request = $this->getRequest();
        if ($request->isGet()) {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }

        $post = $request->getPost();
        $id = $post['id'];

        $service = $this->getPeriodicControlService();
        $periodicControl = $service->findById($id);
        if ($periodicControl && $periodicControl->isDeletable($this->getCurrenUser())) {
            $equipmentInstanceId = $periodicControl->getEquipmentInstance()->getEquipmentInstanceId();
            $service->remove($periodicControl);
            $equipmentInstance = $this->getEquipmentInstanceService()->findById($equipmentInstanceId);
            $service->updateEquipmentInstanceControlData($equipmentInstance);
            $this->getEquipmentInstanceControlDateService()->updateDataByIds(array($equipmentInstanceId));
            $standardMessage = new StandardMessages($this->getTranslator());
            $this->sendTranslatedFlashMessage($standardMessage->deleteSuccessful());
            return $this->redirectToPath('equipment-instance', 'detail', array('id' => $equipmentInstanceId));
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function userIsCompetentWithEquipment($equipment) {
        $equipmentCompetenceVerifier = new EquipmentCompetenceVerifier();
        $userService = $this->getRegisteredInstance('Application\Service\UserService');
        $user = $userService->getUser($this->getCurrenUser()->getUserId());
        return $equipmentCompetenceVerifier->userHasCompetenceWithEquipment($user, $equipment);
    }

    private function addPeriodicControl($post) {
        $controlPointToTemplateArray = $this->getEquipmentInstanceService()
            ->getControlPointToTemplateArray($post->idList);
//        $controlPointCollection = $this->getEquipmentInstanceService()
//                ->getControlPointsByOrder($post->idList);
        $form = $this->getPeriodicControlForm($controlPointToTemplateArray, $post);
        $isPostToSave = is_null($post->postCameFromList);
        if ($isPostToSave) {
            return $this->savePeriodicControls($form, $post, count($controlPointToTemplateArray));
        } else {
            return $this->displayView($form, $post->equipmentIntervalDays);
        }
    }

    public function exportToPdfAction() {
        $periodicControlId = $this->params()->fromRoute('id');
        $periodicControlService = $this->getPeriodicControlService();
        $periodicControl = $periodicControlService->getPeriodicControl($periodicControlId);
        if ($periodicControl !== null) {
            $reportService = $this->getRegisteredInstance('Equipment\Service\PeriodicControlReportService');
            $controlTemplate = $this->getEquipmentInstanceService()->getControlTemplate($periodicControl->getEquipmentInstance());
            $pdfExporter = new PeriodicControlPdfExporter(
                $this->getTranslator(), $controlTemplate);
            $reportService->export($periodicControl, $pdfExporter);
            return $this->response();
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function customValidation($post)
    {
        if(array_key_exists('periodicControlAttachments', $post) && count($post['periodicControlAttachments']) > 0) {
            foreach($post['periodicControlAttachments'] as $postAttachment) {
                if( (!isset($postAttachment['filename']['name']) || empty($postAttachment['filename']['name'])) &&
                    empty($postAttachment['link']) ) {
                    $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The attachments must have a file or a link."), "error");
                    return false;
                }
            }
        }

        return true;
    }

    private function savePeriodicControls($form, $post, $sizeCollection) {
        $form->setData($post);
        if ($form->isValid() && $this->customValidation($post)) {
            $form->bindControlPointResults($post, $sizeCollection);
            $form->bindPeriodicControlAttachments($post);

            $periodicControl = $form->getObject();
            $this->getAttachmentService()->saveFilesAndSetAttachments($form, $post);
            $flashMessengerArray = $this->getPeriodicControlService()
                    ->saveAll($periodicControl, $post->idList, $this->getAttachmentService());

            $this->getEquipmentInstanceControlDateService()->updateDataByIds($post->idList);

            foreach ($flashMessengerArray as $flashMessenger) {
                $this->sendTranslatedFlashMessage($flashMessenger['message'], $flashMessenger['namespace']);
            }
            return $this->redirectToEquipmentInstance($post->equipmentId);
        } else {
            return $this->displayView($form, $post->equipmentIntervalDays);
        }
    }

    private function displayView($form, $days) {
        return array(
            'form' => $form,
            'equipmentIntervalDays' => $days,
            'taxonomies' => $this->getAttachmentService()->getAttachmentTaxonomies()
        );
    }

    private function getNewPeriodicControl() {
        $periodicControl = new \Equipment\Entity\PeriodicControl();
        return $periodicControl;
    }

    private function getPeriodicControlForm($controlPointToTempleArray, $post) {
        $periodicControl = $this->getNewPeriodicControl();
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();

        $formFactory = $this->getFormFactory("Equipment");
        $periodicControlForm = $formFactory->createPeriodicControlForm(
                $controlPointToTempleArray, $userId, $post);
        $periodicControlForm->bind($periodicControl);

        return $periodicControlForm;
    }

    /**
     * @param $id
     * @return Equipment
     */
    private function getEquipment($id) {
        $equipment = $this->getEquipmentService()
                ->getEquipment($id);
        return $equipment;
    }

    /**
     * @return PeriodicControlService
     */
    private function getPeriodicControlService() {
        return $this->getService('Equipment\Service\PeriodicControlService');
    }

    /**
     * @return EquipmentInstanceService
     */
    private function getEquipmentInstanceService() {
        return $this->getRegisteredInstance(
                        'Equipment\Service\EquipmentInstanceService');
    }

    /**
     * @return EquipmentInstanceControlDateService
     */
    private function getEquipmentInstanceControlDateService() {
        return $this->getService('Equipment\Service\EquipmentInstanceControlDateService');
    }

    private function getEquipmentService() {
        return $this->getRegisteredInstance(
                        'Equipment\Service\EquipmentService');
    }

    /**
     * @return \Equipment\Service\PeriodicControlAttachmentService
     */
    private function getAttachmentService()
    {
        return $this->getService('Equipment\Service\PeriodicControlAttachmentService');
    }

    private function redirectToEquipmentInstance($id) {
        return $this->redirectToPath(
                        'equipment-instance', 'index', array('id' => $id)
        );

    }

    /**
     * @param Equipment $equipment
     * @param int $equipmentInstanceId
     */
    private function setBreadcrumbForEquipmentDetail($equipment, $equipmentInstanceId) {
        $applicationName = $this->params()->fromRoute('application');
        $this->setBreadcrumbForFeatureActions($equipment, 'equipment-instance');
        $navigationPageId = $this->getNavigationPage('equipment-instance-detail');
        $navigationPageId->setParams(
            array(
                'application' => $applicationName,
                'id' => $equipmentInstanceId
            )
        );
    }

    public function getApplicationFeatureService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationFeatureService');
    }
}
