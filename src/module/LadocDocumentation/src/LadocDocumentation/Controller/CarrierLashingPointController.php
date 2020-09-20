<?php

namespace LadocDocumentation\Controller;

use LadocDocumentation\Entity\LadocDocumentation;
use Zend\View\Model\ViewModel;
use Application\Service\ServiceOperationException;

class CarrierLashingPointController extends PointBaseController
{
	protected function getNewPoint()
	{
		$documentationId = (int) $this->params()->fromRoute('documentation_id', 0);

        $carrierLashingPoint = $this->getPointService()
                ->getNewPoint($documentationId);
                
        return $carrierLashingPoint;
	}

    protected function getViewTitles(){
        return array(
            'indexTitle' => $this->getTranslator()->translate('Lashing points'),
            'addTitle' => $this->getTranslator()->translate('Add lashing point'),
            'editTitle' => $this->getTranslator()->translate('Edit lashing point'),
        );
    }

    protected function getCollectionAttachmentsIndex()
    {
        return 'carrierLashingPointAttachments';
    }

    protected function createPointForm($formFactory)
    {
    	$lashingPointForm = $formFactory->createCarrierLashingPointForm();
        return $lashingPointForm;
    }

    protected function getPointService()
    {
        return $this->getService('LadocDocumentation\Service\CarrierLashingPointService');
    }

    public function getControllerName(){
        return "carrier-lashing-point";
    }

    protected function redirectToAction($lashingPoint, $action)
    {
        if($action == 'add' || $action == 'index')
            return $this->redirectTo($action, array('documentation_id' => $lashingPoint->getLadocDocumentation()->getId()));
        else
            return $this->redirectTo($action, array('id' => $lashingPoint->getLashingPointId()));
    }

    protected function getCurrentPage() {
        return LadocDocumentation::PAGE_LASHING_POINTS;
    }
}