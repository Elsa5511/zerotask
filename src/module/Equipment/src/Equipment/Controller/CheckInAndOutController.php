<?php

namespace Equipment\Controller;

use Application\Controller\AbstractBaseController;
use Zend\View\Model\ViewModel;

/**
 * This controller is related to Check In and Check Out for Equipment Instances 
 *  
 */
class CheckInAndOutController extends AbstractBaseController
{

    public function detailCheckoutAction()
    {
        $this->layout('layout/iframe');
        $checkoutId = $this->params()->fromRoute('id', 0);
        $checkout = $this->getCheckoutService()->getCheckout($checkoutId);

        if (empty($checkout)) {
            $this->sendFlashMessage("Checkout does not exist", "error");
        } else {
            return new ViewModel(
                    array(
                'checkout' => $checkout
                    )
            );
        }
    }

    /**
     * Add Checkin for Equipment Instances
     * 
     * @return $view array|\Zend\View\Model\ViewModel
     */
    public function checkinAction()
    {
        $equipmentInstanceId = $this->getEvent()
                        ->getRouteMatch()->getParam('id', false);
        $equipmentInstance = $this->getEquipmentInstance($equipmentInstanceId);
        $canBeCheckedIn = $equipmentInstance && $equipmentInstance->isCheckedOut();

        if ($canBeCheckedIn) {
            $this->setBreadcrumbForFeatureActions($equipmentInstance->getEquipment(), 'equipment-instance');
            $checkin = $this->getNewCheckin($equipmentInstance);
            $checkinForm = $this->getCheckinForm($checkin);

            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->saveCheckinData($request->getPost(), $checkinForm, $equipmentInstanceId);
            } else {
                return $this->displayCheckinView($checkinForm, $equipmentInstanceId);
            }
        } else {
            $this->sendFlashMessage("Equipment instance doesn't exist or it's not checked out", "error");
        }
    }

    /**
     * Add Checkout for Equipment Instances
     * 
     * @return $view array|\Zend\View\Model\ViewModel
     */
    public function checkoutAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $post = $request->getPost();
            $checkout = $this->getNewCheckout();
            $checkoutForm = $this->getCheckoutForm($checkout, $post);

            $equipment = $this->getEquipment($post->equipmentId);
            $this->setBreadcrumbForTaxonomy($equipment->getFirstEquipmentTaxonomy());
            
            $isPostToSave = is_null($post->postCameFromList);
            if ($isPostToSave) {
                return $this->saveCheckouts($checkoutForm, $post);
            } else {
                return $this->displayCheckoutView($checkoutForm);
            }
        } else {
            $this->sendFlashMessage("You have not selected any equipment instance", "error");
        }
    }

    private function saveCheckouts($form, $post)
    {
        $form->setData($post);
        if ($form->isValid()) {
            if (is_array($post->idList)) {
                $checkout = $form->getObject();
                $currentUserId = $this->getCurrenUser()->getId();
                $flashMessengerArray = $this->getCheckoutService()
                        ->saveAll($checkout, $post->idList, $currentUserId);
                foreach ($flashMessengerArray as $flashMessenger) {
                    $this->sendFlashMessage(
                            $flashMessenger['message'], $flashMessenger['namespace'], true
                    );
                }
                return $this->redirectToEquipmentInstancesList($post->equipmentId);
            } else {
                $this->displayGenericErrorMessage();
            }
        } else {
            return $this->displayCheckoutView($form);
        }
    }

    /**
     * Validate the post data, then store it
     * or return a validation message
     * 
     * @param type $post
     * @param type $checkinForm
     * @return redirects or display the form
     */
    private function saveCheckinData($post, $checkinForm, $equipmentInstanceId)
    {
        $checkinForm->setData($post);
        $checkin = $checkinForm->getObject();
        if ($checkinForm->isValid()) {
            $this->saveCheckin($checkin);

            $equipmentId = $checkin->getEquipmentInstance()
                            ->getEquipment()->getEquipmentId();
            return $this->redirectToEquipmentInstancesList($equipmentId);
        } else {
            return $this->displayCheckinView($checkinForm, $equipmentInstanceId);
        }
    }

    private function saveCheckin($checkin)
    {
        $serialNumber = $checkin->getEquipmentInstance()->getSerialNumber();
        $currentUserId = $this->getCurrenUser()->getId();
        $resultId = $this->getCheckinService()->persistData($checkin, $currentUserId);
        $translator = $this->getTranslator();

        if ($resultId > 0) {
            $format = $translator->translate('The equipment instance "%s" has been successfully checked in.');
            $namespace = "success";
        } else {
            $format = $translator->translate('The equipment instance "%s" could not be checked in. Try again later.');
            $namespace = "error";
        }
        $message = sprintf($format, $serialNumber);
        $this->sendFlashMessage($message, $namespace, true);
    }

    private function displayCheckoutView($form)
    {
        return array(
            'form' => $form,
            'currentUser' => $this->getCurrenUser()
                    ->getDisplayName(),
        );
    }

    private function displayCheckinView($form, $equipmentInstanceId)
    {
        $checkout = $this->getCheckoutService()
                ->getLastCheckout($equipmentInstanceId);
        return array(
            'form' => $form,
            'checkoutObject' => $checkout,
            'currentUser' => $this->getCurrenUser()
                    ->getDisplayName(),
        );
    }

    /**
     * Get Checkout Form using Form Factory
     * 
     * @param Checkout $checkout
     * @return AuroraForm
     */
    private function getCheckoutForm($checkout, $post)
    {
        $formFactory = $this->getFormFactory('Equipment');
        $checkoutForm = $formFactory->createCheckoutForm($post);
        $checkoutForm->bind($checkout);
        return $checkoutForm;
    }

    /**
     * Get Checkin Form using Form Factory
     * 
     * @param Checkin $checkin
     * @return AuroraForm
     */
    private function getCheckinForm($checkin)
    {
        $formFactory = $this->getFormFactory('Equipment');
        $checkinForm = $formFactory->createCheckinForm();
        $checkinForm->bind($checkin);
        return $checkinForm;
    }

    /**
     * Get a new checkout entity object
     * 
     * @return type
     */
    private function getNewCheckout()
    {
        $checkout = new \Equipment\Entity\Checkout();
        return $checkout;
    }

    /**
     * Get a new checkin entity object
     * 
     * @return type
     */
    private function getNewCheckin($equipmentInstance)
    {
        $checkin = new \Equipment\Entity\Checkin();
        $checkin->setEquipmentInstance($equipmentInstance);
        return $checkin;
    }

    /**
     * Get a equipment instance entity object
     * 
     * @param integer $id
     * @return EquipmentInstance
     */
    private function getEquipmentInstance($id)
    {
        $equipmentInstance = $this->getEquipmentInstanceService()
                ->getEquipmentInstance($id);
        return $equipmentInstance;
    }

    /**
     * @param $id
     * @return \Equipment\Entity\Equipment
     */
    private function getEquipment($id)
    {
        $equipment = $this->getEquipmentService()
                          ->getEquipment($id);
        return $equipment;
    }

    private function getCheckoutService()
    {
        return $this->getRegisteredInstance(
                        'Equipment\Service\CheckoutService');
    }

    private function getCheckinService()
    {
        return $this->getRegisteredInstance(
                        'Equipment\Service\CheckinService');
    }

    private function getEquipmentInstanceService()
    {
        return $this->getRegisteredInstance(
                        'Equipment\Service\EquipmentInstanceService');
    }
    
    private function getEquipmentService()
    {
        return $this->getRegisteredInstance(
                        'Equipment\Service\EquipmentService');
    }

    private function redirectToEquipmentInstancesList($id)
    {
        return $this->redirectToPath(
                        'equipment-instance', 'index', array('id' => $id)
        );
    }

}