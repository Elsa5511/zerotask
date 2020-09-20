<?php

namespace LadocDocumentation\Controller;

use Application\Controller\AbstractBaseController;
use Application\Service\ServiceOperationException;
use LadocDocumentation\Controller\Helper\DocumentEntryViewMapper;
use LadocDocumentation\Entity\BasicInformation;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Service\LadocDocumentationService;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

/**
 * This controller is related to documentation feature
 *
 */
class LadocDocumentationController extends AbstractBaseController {

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
                $searchForms = $controller->forward()->dispatch('Controller\Equipment', array('action' => 'advanced-search', 'application' => $applicationName));
                $controller->layout()->addChild($searchForms, 'searchForms');
            }
        }, -100); // execute after executing action logic

        return $this;
    }

    public function createAction() {
        if ($this->request->isGet()) {
            return $this->createGetAction();
        } else {
            return $this->createPostAction();
        }
    }

    private function createGetAction() {
        $equipmentId = $this->params()->fromRoute('equipment-id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if ($equipment) {
            $this->setBreadcrumbForEquipmentFeature($equipment);
            $ladocDocumentation = $this->getDocumentationService()->findByEquipment($equipmentId);
            return new ViewModel(array(
                'title' => $equipment->getTitle() . ": " . $this->getTranslator()->translate("Create documentation"),
                'equipmentId' => $equipmentId,
                'disableButtons' => $ladocDocumentation !== null
            ));


        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("This equipment doesn't exist"), 'error');
            return $this->redirectToReferer();
        }
    }

    private function createPostAction() {
        $postData = $this->request->getPost();
        $equipmentId = $postData['equipment-id'];
        $type = $postData['type'];
        $service = $this->getDocumentationService();
        try {
            $documentationId = $service->createDocumentation($equipmentId, $type);
            return $this->redirectToAction('wizard', array('id' => $documentationId));
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return $this->redirectToAction('index');
        }
    }

    public function indexAction() {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("This equipment doesn't exist"), 'error');
            return $this->redirectToPath('equipment');
        }

        $this->setBreadcrumbForEquipmentFeature($equipment);

        $service = $this->getDocumentationService();
        $documentation = $service->findByEquipment($equipmentId);

        if ($documentation) {
            if ($documentation->isComplete()) {
                return $this->redirectToAction('display', array('id' => $documentation->getId()));
            } else {
                return $this->redirectToAction('wizard', array('id' => $documentation->getId()));
            }
        } else {
            return $this->redirectToAction('create', array('equipment-id' => $equipmentId));
        }
    }


    public function wizardAction() {
        $service = $this->getDocumentationService();
        $id = $this->params()->fromRoute('id', 0);
        $currentPage = $this->params()->fromRoute('current_page');
        $direction = $this->params()->fromRoute('direction', LadocDocumentation::DIRECTION_NEXT);
        $documentation = $this->getDocumentation($id);
        $nextPage = $service->getNextWizardPage($currentPage, $direction, $documentation->getType());

        if ($nextPage === LadocDocumentation::PAGE_END) {
            $documentation->setFinished(true);
            $service->persist($documentation);
            return $this->redirectToAction('index', array(
                'id' => $documentation->getEquipment()->getEquipmentId()
            ));
        }

        $typePrefix = $documentation->getType() . '-';
        if ($nextPage === LadocDocumentation::PAGE_DOCUMENTATION_ATTACHMENTS) {
            $typePrefix = '';
        }

        $nextAction = 'index';
        if ($nextPage === LadocDocumentation::PAGE_BASIC_INFORMATION
            || $nextPage === LadocDocumentation::PAGE_WEIGHT_AND_DIMENSIONS) {
            $nextAction = 'add';
        }

        return $this->redirectToPath($typePrefix . $nextPage, $nextAction,
            array('documentation_id' => $id));
    }

    public function displayAction() {
        $documentationId = $this->params()->fromRoute('id', 0);
        $service = $this->getDocumentationService();
        $documentation = $service->findById($documentationId);
        if ($documentation) {
            $equipment = $documentation->getEquipment();
            $formattedNsn = $equipment->getFormattedNsn();
            $sapNumber = $equipment->getSap();
            $templateType = $service->getLowestTaxonomyTemplateType($documentation);

            $viewValueMapper = new DocumentEntryViewMapper($documentation);

            $viewModel = new ViewModel(array(
                    'title' => $documentation->getEquipment()->getTitle(),
                    'type' => $documentation->getType(),
                    'basicInformation' => $viewValueMapper->getBasicInformationValues($formattedNsn, $sapNumber),
                    'lashingPoints' => $viewValueMapper->getLashingPointValues(),
                    'liftingPoints' => $viewValueMapper->getLiftingPointValues(),
                    'weightAndDimensions' => $viewValueMapper->getWeightAndDimensionsValues(),
                    'documentationAttachments' => $viewValueMapper->getDocumentationAttachmentValues(),
                    'lashingEquipments' => $viewValueMapper->getLashingEquipmentValues(),
                    'restraintCertifieds' => $viewValueMapper->getRestraintCertifiedValues(),
                    'restraintNonCertifieds' => $viewValueMapper->getRestraintNonCertifiedValues(),
                    'templateType' => $templateType
                )
            );

            $this->setBreadcrumbForEquipmentFeature($equipment);

            return $viewModel;

        } else {
            return $this->redirectToPath('index');
        }
    }

    public function descriptionAction() {
        $documentationService = $this->getDocumentationService();

        $documentationId = (int)$this->params()->fromRoute('documentation_id', 0);
        $type = $this->params()->fromRoute('type', null);

        $ladocDocumentation = $documentationService->findById($documentationId);
        if (!$ladocDocumentation) {
            $this->sendTranslatedFlashMessage($this->getStandardMessages()->ladocDocumentationDoesNotExist(), 'error');
            $this->displayDescriptionForm();
        }

        $request = $this->getRequest();

        $formFactory = $this->getRegisteredInstance('LadocDocumentation\Form\BasicInformationFormFactory');
        $form = $formFactory->createDescriptionInformationForm();

        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            return $this->storePostDescription($form, $post, $ladocDocumentation);
        } else {
            $descriptionValue = $documentationService->getDocumentationDescriptionValue($type, $ladocDocumentation);
            if($descriptionValue)
                $form->get('description_fieldset')->get('description')->setValue($descriptionValue);
            if($type)
                $form->get('description_fieldset')->get('type')->setValue($type);

            return $this->displayDescriptionForm(array('form' => $form, 'documentationId' => $documentationId));
        }
    }

    private function storePostDescription($form, $post, $ladocDocumentation) {
        $documentationService = $this->getDocumentationService();

        $form->setData($post);
        if ($form->isValid()) {
            $type = $post['description_fieldset']['type'];
            $ladocDocumentationDescription = $documentationService->getDocumentationDescription($type, $post['description_fieldset']['description'], $ladocDocumentation);

            if ($ladocDocumentationDescription) {
                $documentationService->persist($ladocDocumentationDescription);
                $this->sendTranslatedFlashMessage($this->getStandardMessages()->saveSucecssful());
                return $this->redirectToReferer();
            } else {
                $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The parameter 'type' is not correct."), 'error');
                return $this->redirectToReferer();
            }
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("The description is not valid."), 'error');
            return $this->redirectToReferer();
        }
    }

    private function displayDescriptionForm($viewValues = array()) {
        $view = new ViewModel($viewValues);
        $view->setTemplate('ladoc-documentation/partial/description-information.phtml');
        return $view;
    }


    /**
     * @param $id
     * @return LadocDocumentation
     */
    private function getDocumentation($id) {
        return $this->getDocumentationService()->findById($id);
    }


    /**
     * @return LadocDocumentationService
     */
    private function getDocumentationService() {
        return $this->getService('LadocDocumentation\Service\LadocDocumentation');
    }

    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    private function redirectToAction($action, $params = array()) {
        return $this->redirectToPath('ladoc-documentation', $action, $params);
    }


}