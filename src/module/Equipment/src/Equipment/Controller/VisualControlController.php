<?php
namespace Equipment\Controller;

use Application\Controller\AbstractBaseController;
use Application\Entity\StandardMessages;
use Equipment\Service\VisualControlService;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

/**
 * This controller is related to Visual Controls for Equipment Instances
 *
 */
class VisualControlController extends AbstractBaseController {

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

    public function indexAction() {
        $id = (int)$this->params()->fromRoute('id', 0);
        $visualControl = $this->getVisualControlService()->findById($id);
        //$equipmentInstanceId = (int)$this->params()->fromRoute('equipmentInstanceId', 0);

        if (empty($visualControl)) {
            $this->sendFlashMessage("Visual Control doesn't exist", 'error');
            return $this->redirectToReferer();
        }

        $equipmentInstance = $visualControl->getEquipmentInstance();
        $instanceSerialNumber = $equipmentInstance->getSerialNumber();
        $equipment = $equipmentInstance->getEquipment();
        $this->setBreadcrumbForEquipmentDetail($equipment, $equipmentInstance->getEquipmentInstanceId());

        $viewModel = new ViewModel(array(
            'title' => $this->getTranslator()->translate('Visual control') . ': ' . $instanceSerialNumber,
            'equipmentType' => $equipment,
            'instance' => $equipmentInstance,
            'visualControl' => $visualControl,
        ));

        $attachmentTable = $this->forward()->dispatch('Controller\PeriodicControlAttachment'
            , array('action' => 'attachment-table', 'id' => $id)
        );
        $viewModel->addChild($attachmentTable, 'attachmentTable');
        return $viewModel;
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

    /**
     * Add Visual Control Instance Action
     *
     * @return $view array|\Zend\View\Model\ViewModel
     */
    public function addAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();

            $validationMessages = $this->getVisualControlService()
                ->checkEnabledVisualControl($post->idList);
            if (count($validationMessages) > 0) {
                return $this->showValidationMessages($validationMessages);
            }

            $equipment = $this->getEquipment($post->equipmentId);
            $this->setBreadcrumbForTaxonomy($equipment->getFirstEquipmentTaxonomy());

            $form = $this->getVisualControlForm($post);

            $isPostToSave = is_null($post->postCameFromList);
            if ($isPostToSave) {
                return $this->saveVisualControls($form, $post);
            } else {
                return $this->displayView($form);
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
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

        $service = $this->getVisualControlService();
        $visualControl = $service->findById($id);

        if ($visualControl && $visualControl->isDeletable($this->getCurrenUser())) {
            $equipmentInstance = $visualControl->getEquipmentInstance();
            $equipmentInstanceId = $equipmentInstance->getEquipmentInstanceId();
            $service->remove($visualControl);
            $service->updateEquipmentInstance($equipmentInstance);
            $sm = new StandardMessages($this->getTranslator());
            $this->sendTranslatedFlashMessage($sm->deleteSuccessful());
            return $this->redirectToPath('equipment-instance', 'detail', array('id' => $equipmentInstanceId));
        }
        else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }


    private function showValidationMessages($messages) {
        foreach ($messages as $flashMessenger) {
            $this->sendFlashMessage(
                $flashMessenger, 'error', true
            );
        }
        return $this->redirectToReferer();
    }


    private function saveVisualControls($form, $post) {
        $form->setData($post);
        if ($form->isValid()) {
            $visualControl = $form->getObject();
            $currenUserId = $this->getCurrenUser()->getId();
            $flashMessengerArray = $this->getVisualControlService()
                ->saveAll($visualControl, $post->idList, $currenUserId);
            foreach ($flashMessengerArray as $flashMessenger) {
                $this->sendFlashMessage(
                    $flashMessenger['message'],
                    $flashMessenger['namespace'],
                    true
                );
            }
            return $this->redirectToEquipmentInstance($post->equipmentId);
        } else {
            return $this->displayView($form);
        }
    }

    private function displayView($form) {
        return array(
            'form' => $form,
            'currentUser' => $this->getCurrenUser()
                ->getDisplayName(),
        );
    }

    private function getVisualControlForm($post) {
        $visualControl = $this->getVisualControlService()->getNewVisualControl();
        $formFactory = $this->getFormFactory("Equipment");
        $visualControlForm = $formFactory->createVisualControlForm($post);
        $visualControlForm->bind($visualControl);

        return $visualControlForm;
    }

    /**
     * @param $id
     * @return \Equipment\Entity\Equipment
     */
    private function getEquipment($id) {
        $equipment = $this->getEquipmentService()
            ->getEquipment($id);
        return $equipment;
    }

    /**
     * @return VisualControlService
     */
    private function getVisualControlService() {
        return $this->getService('Equipment\Service\VisualControlService');
    }

    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    public function getApplicationFeatureService() {
        return $this->getServiceLocator()->get('Application\Service\ApplicationFeatureService');
    }

    private function redirectToEquipmentInstance($id) {
        return $this->redirectToPath(
            'equipment-instance', 'index', array('id' => $id)
        );
    }
}