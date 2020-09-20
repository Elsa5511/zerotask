<?php

namespace Documentation\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;

class CalculatorInfoController extends AbstractBaseController
{
    public function editAction()
    {
        $this->layout('layout/iframe');

        $service = $this->getCalculatorInfoService();
        $formFactory = $this->getFormFactory("Documentation");
        $form = $formFactory->createCalculatorInfoForm();
        $form->bind($service->getData());

        $request = $this->getRequest();
        if ($request->isPost()) {
            return $this->storePostData($request->getPost()->toArray(), $form);
        } else {
            return $this->displayForm($form);
        }
    }

    private function displayForm($form)
    {
        $viewValues = array(
            'form' => $form
        );
        $view = new ViewModel($viewValues);
        $view->setTemplate('documentation/calculator_info/edit.phtml');
        return $view;
    }

    /**
     *
     * @param array $post
     * @param Form $form
     * @return \Zend\View\Model\ViewModel
     */
    private function storePostData($post, $form)
    {
        $service = $this->getCalculatorInfoService();
        $form->setData($post);
        $calculatorInfo = $form->getObject();

        if ($form->isValid()) {
            $service->persistData($calculatorInfo);
            $this->sendFlashMessage($this->translate("The data has been saved."), "success");
            $view = new ViewModel(array(
                "success" => true,
            ));
            $view->setTemplate('documentation/calculator_info/edit.phtml');

            return $view;
        } else {
            return $this->displayForm($form, $calculatorInfo);
        }
    }

    /**
     * @return \Documentation\Service\CalculatorInfoService
     */
    private function getCalculatorInfoService()
    {
        return $this->getService('Documentation\Service\CalculatorInfoService');
    }
}