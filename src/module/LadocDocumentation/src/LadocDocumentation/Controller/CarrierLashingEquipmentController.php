<?php

namespace LadocDocumentation\Controller;


use LadocDocumentation\Entity\LadocDocumentation;

class CarrierLashingEquipmentController extends PointBaseController
{
	protected function getNewPoint()
	{
		$documentationId = (int) $this->params()->fromRoute('documentation_id', 0);

        $carrierLashingEquipment = $this->getPointService()
                ->getNewPoint($documentationId);
                
        return $carrierLashingEquipment;
	}

    protected function getViewTitles(){
        return array(
            'indexTitle' => $this->getTranslator()->translate('Lashing Equipments'),
            'addTitle' => $this->getTranslator()->translate('Add Lashing Equipment'),
            'editTitle' => $this->getTranslator()->translate('Edit Lashing Equipment'),
        );
    }

    protected function getCollectionAttachmentsIndex()
    {
        return 'carrierLashingEquipmentAttachments';
    }

    protected function createPointForm($formFactory)
    {
    	$carrierLashingEquipmentForm = $formFactory->createLashingEquipmentForm();
        return $carrierLashingEquipmentForm;
    }

    protected function getPointService()
    {
        return $this->getService('LadocDocumentation\Service\CarrierLashingEquipmentService');
    }

    public function getControllerName(){
        return "carrier-lashing-equipment";
    }

    protected function redirectToAction($lashingEquipment, $action)
    {
        if($action == 'add' || $action == 'index')
            return $this->redirectTo($action, array('documentation_id' => $lashingEquipment->getLadocDocumentation()->getId()));
        else
            return $this->redirectTo($action, array('id' => $lashingEquipment->getLashingEquipmentId()));
    }

    protected function getCurrentPage() {
        return LadocDocumentation::PAGE_LASHING_EQUIPMENT;
    }
}