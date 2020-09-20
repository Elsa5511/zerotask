<?php

namespace Certification\Controller;

use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;

/**
 * 
 */
class CertificationController extends AbstractBaseController {

    /**
     * 
     */
    public function indexAction() {
        $equipmentId = (int) $this->params()->fromRoute('id', 0);
        $certifications = $this->getCertificationService()->findByEquipment($equipmentId);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The equipment type does not exist'), 'error');
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);

        return array(
            'title' => $equipment->getTitle() . ': ' . $this->getTranslator()->translate('Certifications'),
            'certifications' => $certifications,
            'equipmentId' => $equipmentId
        );
    }

    /**
     * 
     * @return View
     */
    public function addAction() {
        $equipmentId = (int) $this->params()->fromRoute('id', 0);
        $certification = $this->getNewCertification($equipmentId);

        if ($certification) {
            $this->setBreadcrumbForFeatureActions($certification->getEquipment(), 'certification');
            return $this->managePostSave($certification);
        } else {
            $this->sendFlashMessage("Equipment doesn't exist", "error");
            return $this->redirectToIndex($equipmentId);
        }
    }

    /**
     * 
     * @return type
     */
    public function editAction() {
        $certificationId = $this->params()->fromRoute('id', false);
        $certification = $this->getCertification($certificationId);

        if ($certification) {
            $this->setBreadcrumbForFeatureActions($certification->getEquipment(), 'certification');
            return $this->managePostSave($certification);
        } else {
            $this->sendFlashMessage($this->getTranslator()->translate("Certification doesn't exist"), "error");
            return $this->redirectToReferer();
        }
    }

    private function managePostSave($certification) {
        $form = $this->getCertificationForm($certification);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                return $this->storePostData($certification);
            }
        }
        return $this->displayView($form, $certification);
    }

    /**
     * Display Certification form
     * 
     * @param type $taxonomyForm
     * @return ViewModel $view
     */
    private function displayView($form, $certification) {
        $equipmentName = $certification->getEquipment()->getTitle();
        $certificationId = $certification->getCertificationId();
        $viewValues = array(
            'form' => $form,
            'equipmentName' => $equipmentName,
            'certificationId' => $certificationId
        );

        $view = new ViewModel($viewValues);
        if (empty($certificationId)) {
            $view->setTemplate('certification/certification/edit.phtml');
        }

        return $view;
    }

    /**
     * 
     * @param type $post
     * @param type $form
     * @return \Zend\View\Model\ViewModel
     */
    private function storePostData($certification) {
        $equipmentId = $certification->getEquipment()->getEquipmentId();
        $resultId = $this->getCertificationService()->persistData($certification);
        if ($resultId > 0) {
            $this->sendFlashMessage($this->getTranslator()->translate("Certification has been saved."), "success");
        } else {
            $this->displayGenericErrorMessage();
        }
        return $this->redirectToIndex($equipmentId);
    }

    /**
     * 
     * @return type
     */
    public function deleteAction() {
        $certificationId = (int) $this->params()->fromRoute('id', 0);
        if ($certificationId > 0) {
            $flashMessengerArray = $this->getCertificationService()->deleteById($certificationId);
            $this->sendFlashMessage($flashMessengerArray['message'], $flashMessengerArray['namespace'], true);
        } else {
            $this->displayGenericErrorMessage();
        }
        return $this->redirectToIndex();
    }

    /**
     * Delete many Certifications
     * Call by js 
     * 
     * @return a success message
     */
    public function deleteManyAction() {
        $post = $this->getRequest()->getPost();
        $certificationIds = $post->idList;

        if ($certificationIds) {
            $flashMessengerArray = $this->getCertificationService()->deleteByIds($certificationIds);
            foreach ($flashMessengerArray as $flashMessenger) {
                $this->sendFlashMessage($flashMessenger['message'], $flashMessenger['namespace'], true);
            }
        } else {
            $this->displayGenericErrorMessage();
        }
        return $this->redirectToIndex();
    }

    public function userAction() {
        $userId = $this->params()->fromRoute('id', false);
        $user = $this->getUser($userId);

        if ($user) {
            $certifications = $this->getCertificationService()->findByUser($user->getId());
            if(count($certifications) > 0) {
                $this->setBreadcrumbForFeatureActions(
                        $certifications[0]->getEquipment(), 'certification');
            }

            return array(
                'user' => $user,
                'certifications' => $certifications
            );
        } else {
            $this->sendFlashMessage($this->getTranslator()->translate("User doesn't exist"), "error");
            $this->redirectToIndex();
        }
    }

    /**
     * 
     * @return array View values
     */
    public function reportAction() {
        $searchForm = $this->getCertificationSearchForm();

        $request = $this->getRequest();
        $isSearch = $request->isPost();
        if ($isSearch) {
            $post = $request->getPost();
            $filterParams = $post->get('certification-search');
            if (is_null($filterParams)) {
                $filterParams = array();
            }
            $searchForm->setData($post);

            $certifications = $this->getCertificationService()->search($filterParams);
        } else {
            $filterParams = array();
            $certifications = $this->getCertificationService()->findAll();
        }

        return array(
            'certifications' => $certifications,
            'searchForm' => $searchForm,
            'isSearch' => $isSearch,
            'currentFilterParams' => $filterParams
        );
    }

    // TODO: Put in abstract base controller?
    public function exportReportAction() {
        $post = $this->getRequest()->getPost();
        $filterParams = $post->get('filter-params');
        $exportType = $post->get('exportType');
        if ($filterParams === null) {
            $certifications = $this->getCertificationService()->findAll();
        } else {
            $certifications = $this->getCertificationService()->search($filterParams);
        }
        $report = $this->getCertificationService()->createReportTable($certifications);
        $this->exportReport($report, $exportType);
        return $this->response;
    }

    /**
     * Called by a cron job
     */
    public function updateAfterExpireAction() {
        $this->getCertificationService()->updateCertificationAfterExpire();
    }

    /**
     * 
     * @param type $userId
     * @return type
     */
    private function getUser($userId) {
        $user = $this->getUserService()
                ->getUser($userId);
        return $user;
    }

    /**
     * 
     * @param type $certificationId
     * @return type
     */
    private function getCertification($certificationId) {
        $certification = $this->getCertificationService()
                ->findById($certificationId);
        return $certification;
    }

    /**
     * 
     * @param type $equipmentId
     * @return \Certification\Entity\Certification
     */
    private function getNewCertification($equipmentId) {
        $certification = $this->getCertificationService()
                ->getNewCertification($equipmentId);
        return $certification;
    }

    /**
     * 
     * @param type $certification
     * @return Form
     */
    private function getCertificationForm($certification) {
        $formFactory = $this->getFormFactory("Certification");
        $form = $formFactory->createCertificationForm();
        $form->bind($certification);
        return $form;
    }

    /**
     * 
     * @param type $certification
     * @return Form
     */
    private function getCertificationSearchForm() {
        $formFactory = $this->getFormFactory("Certification");
        $form = $formFactory->createCertificationSearchForm();
        return $form;
    }

    /**
     * 
     * @param type $equipmentId
     * @return type
     */
    private function redirectToIndex($equipmentId = 0) {
        if ($equipmentId > 0) {
            return $this->redirectToPath('certification', 'index', array('id' => $equipmentId));
        } else {
            $request = $this->getRequest();
            $httpReferer = $request->getServer('HTTP_REFERER');
            if ($httpReferer) {
                return $this->redirect()->toUrl($httpReferer);
            }
        }
    }

    private function getCertificationService() {
        return $this->getService('Certification\Service\CertificationService');
    }

    private function getUserService() {
        return $this->getRegisteredInstance
                        ('Application\Service\UserService');
    }

    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

}
