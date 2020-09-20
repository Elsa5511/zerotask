<?php

namespace Documentation\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

/**
 * This controller is related to documentation feature
 *  
 */
class DocumentationController extends AbstractBaseController
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
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate("This equipment doesn't exist"), 'error');
            return $this->redirectToPath('equipment');
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);

        $sectionId = $this->params()->fromRoute('sectionId', 0);
        $currentSection = array();
        
        if ($sectionId) {
            $currentSection = $this->getDocumentationSectionService()->getSection($sectionId);         
        }
        if (empty($currentSection)) {       
            $currentSection = $this->getDocumentationSectionService()->getFirstContentSection($equipmentId,'equipment');
            $sectionId = $currentSection ? $currentSection->getSectionId() : null;
        }
    
        $sections = $this->getDocumentationSectionService()->getParentSections($equipmentId,'equipment');
        $inlineSections = $this->getInlineSectionService()->getInlineSections($sectionId,'documentation');

        return new ViewModel(
                array(
            'title' => $equipment->getTitle() . ': ' . $this->getTranslator()->translate('Documentation'),
            'equipmentId' => $equipmentId,
            'sections' => $sections,
            'currentSection' => $currentSection,
            'inlineSections' => $inlineSections
                )
        );
    }

    private function getDocumentationSectionService()
    {
        return $this->getService('Documentation\Service\DocumentationSectionService');
    }

    private function getInlineSectionService()
    {
        return $this->getService('Documentation\Service\InlineSectionService');
    }

    private function getEquipmentService()
    {
        return $this->getService('Equipment\Service\EquipmentService');
    }

}