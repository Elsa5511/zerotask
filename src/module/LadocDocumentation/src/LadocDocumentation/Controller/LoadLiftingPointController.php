<?php

namespace LadocDocumentation\Controller;

use LadocDocumentation\Entity\LadocDocumentation;

class LoadLiftingPointController extends PointBaseController
{
	protected function getNewPoint()
	{
		$documentationId = (int) $this->params()->fromRoute('documentation_id', 0);

        $loadLiftingPoint = $this->getPointService()
                ->getNewPoint($documentationId);
                
        return $loadLiftingPoint;
	}

    protected function getViewTitles(){
        return array(
            'indexTitle' => $this->getTranslator()->translate('Lifting Points'),
            'addTitle' => $this->getTranslator()->translate('Lifting Point'),
            'editTitle' => $this->getTranslator()->translate('Lifting Point'),
        );
    }

    protected function getCollectionAttachmentsIndex()
    {
        return 'loadLiftingPointAttachments';
    }

    protected function createPointForm($formFactory)
    {
    	$liftingPointForm = $formFactory->createLoadLiftingPointForm();
        return $liftingPointForm;
    }

    protected function getPointService()
    {
        return $this->getService('LadocDocumentation\Service\LoadLiftingPointService');
    }

    public function getControllerName(){
        return "load-lifting-point";
    }

    protected function redirectToAction($liftingPoint, $action)
    {
        if($action == 'add' || $action == 'index')
            return $this->redirectTo($action, array('documentation_id' => $liftingPoint->getLadocDocumentation()->getId()));
        else
            return $this->redirectTo($action, array('id' => $liftingPoint->getLiftingPointId()));
    }

    protected function getCurrentPage() {
        return LadocDocumentation::PAGE_LIFTING_POINTS;
    }
}