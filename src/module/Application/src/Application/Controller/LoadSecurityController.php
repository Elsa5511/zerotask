<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

class LoadSecurityController extends AbstractBaseController 
{

    public function indexAction()
    {
        if ($this->params()->fromRoute('modal')) {
            $this->layout('layout/iframe');
        } else {
            $this->setBreadcrumbForApplication();
        }

        $view = new ViewModel(array(
            'calculatorInfo' => $this->getCalculatorInfoService()->getData(),
            'from_modal' => $this->params()->fromRoute('modal')
        ));
        return $view;
    }

    /**
     * @return \Documentation\Service\CalculatorInfoService
     */
    private function getCalculatorInfoService()
    {
        return $this->getService('Documentation\Service\CalculatorInfoService');
    }
}