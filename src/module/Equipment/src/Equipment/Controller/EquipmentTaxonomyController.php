<?php
namespace Equipment\Controller;

use Application\Controller\Helper\DeactivationHelper;
use Equipment\Entity\EquipmentTaxonomy;
use Equipment\Form\FormFactory;
use Equipment\Service\EquipmentTaxonomyService;
use Zend\View\Model\ViewModel;
use Application\Controller\AbstractBaseController;

class EquipmentTaxonomyController extends AbstractBaseController
{
    /**
     * @return EquipmentTaxonomyService
     */
    private function getEquipmentTaxonomyService()
    {
        return $this->getService('Equipment\Service\EquipmentTaxonomyService');
    }

    public function adminIndexAction() {
        $categories = $this->getEquipmentTaxonomyService()->findAll();
        return new ViewModel(array(
            'title' => $this->translate('Categories'),
            'categories' => $categories
        ));
    }


    public function deleteAction()
    {
        $equipmentTaxonomyId = $this->params()->fromRoute('id', 0);

        if ($equipmentTaxonomyId > 0) {            
            $flashMessengerArray = $this->getEquipmentTaxonomyService()->deleteById($equipmentTaxonomyId);
            $this->sendFlashMessage($flashMessengerArray['message'], 
                                    $flashMessengerArray['namespace'], true);
        } else {
            $this->sendFlashMessage('Incorrect category id format', 'error');
        }

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }
    
    /**
     * 
     * 
     * @return view
     */
    public function addAction() {
        $this->layout('layout/iframe');
        $parentTaxonomyId = (int) $this->params()->fromRoute('parent_id', 0);
        $taxonomy = $this->getNewTaxonomy($parentTaxonomyId);
        $taxonomyForm = $this->getTaxonomyForm($taxonomy);
        
        return $this->managePostSave($taxonomyForm, $taxonomy);
    }
    
    /**
     * 
     * @return view
     */
    public function editAction()
    {
        $this->layout('layout/iframe');
        $taxonomyId = $this->params()->fromRoute('id', false);
        
        $taxonomy = $this->getTaxonomy($taxonomyId);
        if ($taxonomy) {
            $taxonomyForm = $this->getTaxonomyForm($taxonomy);
            return $this->managePostSave($taxonomyForm, $taxonomy);
        } else {
            return $this->displayGenericErrorMessage();
        }
    }

    public function deactivateAction() {
        $post = $this->getRequest()->getPost();
        $id = $post['id'];
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateAction($id);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function reactivateAction() {
        $post = $this->getRequest()->getPost();
        $id = $post['id'];
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->activateAction($id);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    public function deactivateManyAction() {
        $post = $this->getRequest()->getPost();
        $ids = explode(',', $post['id']);
        $deactivationHelper = $this->getDeactivationHelper();
        $deactivationHelper->deactivateManyAction($ids);

        $url = $this->getRequest()->getHeader('Referer')->getUri();
        return $this->redirect()->toUrl($url);
    }

    /**
     * 
     * @param type $taxonomyForm
     * @param EquipmentTaxonomy $taxonomy
     * @return mixed
     */
    private function managePostSave($taxonomyForm, $taxonomy)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = array_merge_recursive(
                    $request->getPost()->toArray(), 
                    $request->getFiles()->toArray()
            );
            return $this->storePostData($post, $taxonomyForm);
        } else {
            return $this->displayForm($taxonomyForm, $taxonomy);
        }
    }
    
    /**
     * Display Equipment taxonomy form
     * 
     * @param type $form
     * @param $taxonomy EquipmentTaxonomy
     * @return ViewModel $view
     */
    private function displayForm($form, $taxonomy)
    {
        $viewValues = array(
            'templateType' => $taxonomy->getClosestTemplateType(),
            'featureImage' => $taxonomy->getFeaturedImage(),
            'form' => $form
        );
        return new ViewModel($viewValues);
    }
    
    /**
     * 
     * @param type $post
     * @param type $taxonomyForm
     * @return \Zend\View\Model\ViewModel
     */
    private function storePostData($post, $taxonomyForm) {
        $taxonomyForm->setData($post);
        $taxonomy = $taxonomyForm->getObject();
        
        if($taxonomyForm->isValid()) {
            $taxonomyService = $this->getEquipmentTaxonomyService();
            if ($post["remove_image"] == 1) {
                $taxonomyService->removeTaxonomyImage($taxonomy->getFeaturedImage());
                $taxonomy->setFeaturedImage(null);
            }

            if(empty($taxonomy->getTemplateType())) $taxonomy->setTemplateType(null);

            $taxonomyService->persistData($taxonomy, $post["equipment_taxonomy"]);
            $this->sendFlashMessage("The category has been saved.", "success");
            
            // TODO use only one template
            return new ViewModel(array(
                "success" => true,
            ));
        } else {
            return $this->displayForm($taxonomyForm, $taxonomy);
        }
    }
    
    /**
     * 
     * @param int $taxonomyId
     * @return EquipmentTaxonomy
     */
    private function getTaxonomy($taxonomyId)
    {
        $taxonomy = $this->getEquipmentTaxonomyService()
                ->findById($taxonomyId);
        return $taxonomy;
    }
    
    /**
     * 
     * @param type $parentId
     * @return \Equipment\Entity\EquipmentTaxonomy
     */
    private function getNewTaxonomy($parentId)
    {
        $taxonomy = $this->getEquipmentTaxonomyService()
                ->getNewTaxonomy($parentId);
        return $taxonomy;
    }

    /**
     * @param $taxonomy
     * @return \Sysco\Aurora\Form\Form
     */
    private function getTaxonomyForm($taxonomy)
    {        
        $equipmentTaxonomyId = $taxonomy->getEquipmentTaxonomyId();
        /**
         * @var $formFactory FormFactory
         */
        $formFactory = $this->getFormFactory("Equipment");
        $application = $this->params()->fromRoute('application');
        $form = $formFactory->createEquipmentTaxonomyForm($equipmentTaxonomyId, $application);
        $form->bind($taxonomy);
        return $form;
    }

    private function getDeactivationHelper() {
        return new DeactivationHelper($this, $this->getEquipmentTaxonomyService(), $this->getTranslator());
    }
}
