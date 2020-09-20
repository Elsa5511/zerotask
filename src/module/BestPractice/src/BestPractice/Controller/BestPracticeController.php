<?php

namespace BestPractice\Controller;

use Application\Controller\AbstractBaseController;
use Application\Service\EntityDoesNotExistException;
use Zend\View\Model\ViewModel;
use BestPractice\Entity\BestPractice;
use Application\Service\ServiceOperationException;

class BestPracticeController extends AbstractBaseController {

    public function indexAction() {
        $equipmentId = (int) $this->params()->fromRoute('id', 0);
        $equipment = $this->getEquipmentService()->getEquipment($equipmentId);
        if (empty($equipment)) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('The equipment does not exist'), 'error');
            return $this->redirectToReferer();
        }
        $this->setBreadcrumbForEquipmentFeature($equipment);
        $bestPracticeService = $this->getBestPracticeService();
        $bestPractices = $bestPracticeService->getLastRevisionsByEquipment($equipmentId);

        return array(
            'bestPractices' => $bestPractices,
            'equipmentId' => $equipmentId,
        );
    }

    public function proceduresAction() {
        return $this->attachmentsView("findProcedures");
    }

    public function additionalInfoAction() {
        return $this->attachmentsView("findAdditionalInfo");
    }

    public function userManualAction() {
        return $this->attachmentsView("findUserManual");
    }

    public function proceduresOldRevisionAction() {
        $viewArray = $this->attachmentsView("findProcedures");
        return $this->manageView($viewArray, "best-practice/best-practice/procedures.phtml");
    }

    public function additionalInfoOldRevisionAction() {
        $viewArray = $this->attachmentsView("findAdditionalInfo");
        return $this->manageView($viewArray, "best-practice/best-practice/additional-info.phtml");
    }

    public function userManualOldRevisionAction() {
        $viewArray = $this->attachmentsView("findUserManual");
        return $this->manageView($viewArray, "best-practice/best-practice/user-manual.phtml");
    }

    private function attachmentsView($methodName) {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        $bestPracticeService = $this->getBestPracticeService();
        $bestPractice = $bestPracticeService->findById($bestPracticeId);

        if ($bestPractice) {
            $isLastRevision = $bestPracticeService->isLastRevision($bestPractice);
            if ($isLastRevision) {
                $this->setBreadcrumbForFeatureActions($bestPractice->getEquipment(), 'best-practice');
            } else {
                $lastRevision = $bestPracticeService->getLastRevisionByIdentifier($bestPractice->getIdentifier());
                $this->setBreadcrumbForHistory($lastRevision);
            }
            $attachments = $bestPracticeService->$methodName($bestPracticeId);
            return array(
                'bestPracticeId' => $bestPractice->getBestPracticeId(),
                'title' => $bestPractice->getTitle() . ': ' . $bestPractice->getSubtitle(),
                'attachments' => $attachments,
                'revisionNumber' => $bestPractice->getRevisionNumber(),
                'isLastRevision' => $isLastRevision
            );
        } else {
            $errorMessage = $this->entityDoesNotExistMessage($bestPracticeId);
            $this->sendTranslatedFlashMessage($errorMessage, "error");
            return $this->redirectToReferer();
        }
    }

    public function detailAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        $bestPracticeService = $this->getBestPracticeService();
        $bestPractice = $bestPracticeService->findById($bestPracticeId);

        try {
            if ($bestPractice === null) {
                $errorMessage = $this->entityDoesNotExistMessage($bestPracticeId);
                throw new EntityDoesNotExistException($errorMessage);
            }

            $isLastRevision = $bestPracticeService->isLastRevision($bestPractice);
            if ($isLastRevision) {
                $this->setBreadcrumbForFeatureActions($bestPractice->getEquipment(), 'best-practice');
                $subscriptionService = $this->getSubscriptionService();
                $subscription = $subscriptionService->getSubscription($this->getCurrenUser(), $bestPractice);
                $configArray = $this->getConfigArray();
                $adminEmail = str_replace('@', "&#64;", $configArray["admin-email"]);
            } else {
                $lastRevision = $bestPracticeService->getLastRevisionByIdentifier($bestPractice->getIdentifier());
                $this->setBreadcrumbForHistory($lastRevision);
                $subscription = null;
                $adminEmail = "";
            }

            return array(
                'bestPracticeId' => $bestPractice->getBestPracticeId(),
                'title' => $bestPractice->getTitle() . ': ' . $bestPractice->getSubtitle(),
                'slides' => $bestPractice->getValidSlides(),
                'isSubscribed' => !empty($subscription),
                'adminEmail' => $adminEmail,
                'revisionNumber' => $bestPractice->getRevisionNumber(),
                'isLastRevision' => $isLastRevision
            );
        } catch (ServiceOperationException $exception) {
            $this->manageException($exception);
            return $this->redirectToReferer();
        }
    }

    public function oldRevisionDetailAction() {
        $viewArray = $this->detailAction();
        return $this->manageView($viewArray, "best-practice/best-practice/detail.phtml");
    }

    public function revisionHistoryAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        $bestPracticeService = $this->getBestPracticeService();
        $bestPractice = $bestPracticeService->findById($bestPracticeId);

        if ($bestPractice) {
            $this->setBreadcrumbForDetail($bestPractice);

            $oldRevisions = $bestPracticeService->getOldRevisions($bestPractice->getIdentifier());
            return array(
                'controller' => 'best-practice',
                'bestPracticeId' => $bestPractice->getBestPracticeId(),
                'title' => $bestPractice->getTitle() . ': ' . $bestPractice->getSubtitle(),
                'oldRevisions' => $oldRevisions
            );
        } else {
            $message = $this->entityDoesNotExistMessage($bestPracticeId);
            $this->sendTranslatedFlashMessage($message, "error");
            return $this->redirectToReferer();
        }
    }

    public function addAction() {
        $equipmentId = (int) $this->params()->fromRoute('id', 0);
        $bestPracticeService = $this->getBestPracticeService();

        try {
            $bestPractice = $bestPracticeService->getNewBestPractice($equipmentId);
            $this->setBreadcrumbForFeatureActions($bestPractice->getEquipment(), 'best-practice');
            if ($this->isHttpPostRequest()) {
                return $this->handleAddEditPostRequest($bestPractice);
            } else {
                return $this->handleAddEditGetRequest($bestPractice);
            }
        } catch (EntityDoesNotExistException $exception) {
            $this->manageException($exception);
            return $this->redirectToReferer();
        }
    }

    public function deleteAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);

        try {
            $this->getBestPracticeService()->deleteBestPractice($bestPracticeId, $this->getAttachmentService());
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Best Practice has been deleted successfully.'));
        } catch (ServiceOperationException $exception) {
            $this->sendTranslatedFlashMessage($exception->getMessage(), 'error');
            return $this->redirectToReferer();
        }

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function editAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        $bestPracticeService = $this->getBestPracticeService();

        $bestPractice = $bestPracticeService->findById($bestPracticeId);
        if ($bestPractice) {
            $this->setBreadcrumbForFeatureActions($bestPractice->getEquipment(), 'best-practice');
            if ($this->isHttpPostRequest()) {
                return $this->handleAddEditPostRequest($bestPractice, true);
            } else {
                return $this->handleAddEditGetRequest($bestPractice);
            }
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function isHttpPostRequest() {
        return $this->getRequest()->isPost();
    }

    public function subscribeAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        return $this->manageSubscription($bestPracticeId, true);
    }

    public function unsubscribeAction() {
        $bestPracticeId = (int) $this->params()->fromRoute('id', 0);
        return $this->manageSubscription($bestPracticeId, false);
    }

    public function newRevisionNotificationsAction() {
        $this->getSubscriptionCronService()->notifyNewRevisionsToSubscribers();
        return $this->response;
    }

    /**
     * 
     * @param int $bestPracticeId
     * @param boolean $subscribe
     * @return $mixed
     */
    private function manageSubscription($bestPracticeId, $subscribe) {
        try {
            $result = $this->getSubscriptionService()
                    ->manageSubscription($bestPracticeId, $this->getCurrenUser()->getUserId(), $subscribe);
            $this->sendFlashMessage($result->getMessage(), $result->getMessageType());
        } catch (EntityDoesNotExistException $exception) {
            $this->manageException($exception);
        }

        return $this->redirectToPath("best-practice", "detail", array("id" => $bestPracticeId));
    }

    private function handleAddEditGetRequest($bestPractice) {
        $bestPracticeForm = $this->getBestPracticeForm($bestPractice);
        return array(
            'form' => $bestPracticeForm
        );
    }

    /**
     * 
     * @param \BestPractice\Entity\BestPractice $currentBestPractice
     * @return $mixed
     */
    private function handleAddEditPostRequest($currentBestPractice, $isEditMode = false) {
        $bestPracticeForm = $this->getBestPracticeForm($currentBestPractice);
        $post = $this->mergePostValuesAndFiles();
        $fieldsetPost = $post["best-practice"];
        $saveAsNewRevision = isset($fieldsetPost["new-revision"]) && $fieldsetPost["new-revision"] == 1;
        $slideOnePost = $fieldsetPost["slide-one"];
        $bestPracticeToSave = $currentBestPractice;
        $previousRevisionId = null;
        if ($isEditMode) {
            if ($saveAsNewRevision) {
                $previousRevisionId = $currentBestPractice->getBestPracticeId();
                $bestPracticeToSave = $bestPracticeForm
                        ->createNewRevision($currentBestPractice, $fieldsetPost["revision-comment"]);
            }
            $bestPracticeForm->verifyFilesValidation($slideOnePost);
        }

        $currentFeaturedImage = $bestPracticeToSave->getFeaturedImage();
        $bestPracticeForm->setData($post);
        if ($bestPracticeForm->isValid()) {
            $slides[] = $slideOnePost;
            $slides[] = $fieldsetPost["slide-two"];
            $this->setImagesFor($bestPracticeToSave, $slides, $currentFeaturedImage);
            return $this->saveBestPractice($bestPracticeToSave, $saveAsNewRevision, $previousRevisionId);
        } else {
            return array(
                'form' => $bestPracticeForm
            );
        }
    }

    private function mergePostValuesAndFiles() {
        $request = $this->getRequest();
        $basePost = $request->getPost()->toArray();
        $filesPost = $request->getFiles()->toArray();
        return array_merge_recursive($basePost, $filesPost);
    }

    private function setImagesFor($bestPractice, $slides, $featuredImage) {
        $bestPracticeService = $this->getBestPracticeService();
        $bestPracticeService
                ->manageFeaturedImage($bestPractice, $featuredImage);
        $bestPracticeService
                ->manageSlideImagesFromPost($bestPractice, $slides);
    }

    private function saveBestPractice($bestPractice, $isNewRevision = false, $previousRevisionId = null) {
        $equipmentId = $bestPractice->getEquipment()->getEquipmentId();
        $subscriptionService = $this->getSubscriptionService();
        $bestPracticeService = $this->getBestPracticeService();

        $resultId = $bestPracticeService->persistData($bestPractice);

        if ($resultId > 0) {
            if ($isNewRevision) {
                $subscriptionService->updateSubscribersPendingNotification($bestPractice->getIdentifier(), $resultId);
                $bestPracticeService->copyAttachments($bestPractice, $previousRevisionId, $this->getAttachmentService());
            }
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Best Practice has been successfully saved.'));
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Best Practice could not be saved at this time.'), 'error');
        }
        return $this->redirectToIndex($equipmentId);
    }

    private function redirectToIndex($equipmentId) {
        return $this->redirectToPath(
                        'best-practice', 'index', array('id' => $equipmentId));
    }

    public function exportToPdfAction() {
        $bestPracticeId = $this->params()->fromRoute('id');
        $bestPracticeService = $this->getBestPracticeService();
        $bestPractice = $bestPracticeService->findById($bestPracticeId);
        if ($bestPractice !== null) {
            $bestPracticeExporter = $this->getRegisteredInstance('BestPractice\Service\BestPracticeExporter');
            $bestPracticeService->exportToPdf($bestPractice, $bestPracticeExporter);
            return $this->response;
        } else {
            $this->displayGenericErrorMessage();
            return $this->redirectToReferer();
        }
    }

    private function setBreadcrumbForDetail(BestPractice $bestPractice) {
        $this->setBreadcrumbForFeatureActions($bestPractice->getEquipment(), 'best-practice');
        $applicationName = $this->params()->fromRoute('application');
        $navigationPage = $this->getNavigationPage('best-practice-detail');
        $navigationPage->setParams(
                array(
                    'application' => $applicationName,
                    'id' => $bestPractice->getBestPracticeId()
                )
        );
    }

    private function setBreadcrumbForHistory(BestPractice $bestPractice) {
        $this->setBreadcrumbForDetail($bestPractice);
        $applicationName = $this->params()->fromRoute('application');
        $navigationPage = $this->getNavigationPage('revision-history');
        $navigationPage->setParams(
                array(
                    'application' => $applicationName,
                    'id' => $bestPractice->getBestPracticeId()
                )
        );
    }

    private function manageView($data, $template) {
        if (is_array($data)) {
            $view = new ViewModel($data);
            $view->setTemplate($template);
            return $view;
        } else {
            return $data;
        }
    }

    /**
     * 
     * @param type $bestPractice
     * @return Form
     */
    private function getBestPracticeForm($bestPractice) {
        $formFactory = $this->getFormFactory("BestPractice");
        $form = $formFactory
                ->createBestPracticeForm($bestPractice->getBestPracticeId());
        $form->bind($bestPractice);
        return $form;
    }

    private function entityDoesNotExistMessage($bestPracticeId) {
        return sprintf($this->getTranslator()->translate("Could not find Best Practice with id %u"), $bestPracticeId);
    }

    private function getBestPracticeService() {
        return $this->getService('BestPractice\Service\BestPracticeService');
    }

    private function getSubscriptionService() {
        return $this->getRegisteredInstance('BestPractice\Service\SubscriptionService');
    }

    private function getEquipmentService() {
        return $this->getService('Equipment\Service\EquipmentService');
    }

    private function getSubscriptionCronService() {
        return $this->getServiceLocator()->get('BestPractice\Service\SubscriptionCronService');
    }

    public function getAttachmentService() {
        return $this->getService('BestPractice\Service\BestPracticeAttachmentService');
    }

}
