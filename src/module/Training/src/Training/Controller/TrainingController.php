<?php

namespace Training\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

/**
 * This controller is related to training instances 
 *  
 */
class TrainingController extends AbstractBaseController
{

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events)
    {
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

    public function indexAction()
    {
        $equipmentId = $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendFlashMessage('The equipment type does not exist', 'error');            
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);
        $sections = $this->getTrainingSectionService()->getParentSections($equipmentId,'equipment');

        return new ViewModel(
                array(
            'title' => $equipment->getTitle() . ': ' . $this->getTranslator()->translate('Training'),
            'equipmentId' => $equipmentId,
            'sections' => $sections,
                )
        );
    }

    private function getTrainingSectionService()
    {
        return $this->getService(
                        'Training\Service\TrainingSectionService');
    }

    private function getEquipmentService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }

}