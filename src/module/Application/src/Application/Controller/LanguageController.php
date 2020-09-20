<?php
namespace Application\Controller;

use Zend\View\Model\ViewModel;

class LanguageController extends AbstractBaseController
{

    /**
     * List of Languages
     */
    public function indexAction()
    {
        $languages = $this->getLanguageService()->fetchAll();
        $title = $this->getAdminTitle() . $this->getTranslator()->translate('Languages');
        return new ViewModel(array(
            'languages' => $languages,
            'title' => $title
        ));
    }

    /**
     * Add Language Form
     *
     * @return ViewModel $view
     */
    public function addAction()
    {
        $language = $this->getNewLanguage();
        $languageForm = $this->getLanguageForm($language);

        $request = $this->getRequest();
        if ($request->isPost()) {
            return $this->storePostData($request->getPost(), $languageForm);
        } else {
            return $this->displayForm($languageForm);
        }
    }

    /**
     * Edit Language Action
     * 
     */
    public function editAction()
    {
        $languageId = $this->getEvent()
                        ->getRouteMatch()->getParam('id', false);
        $language = $this->getLanguage($languageId);

        if ($language) {
//            $page = $this->getNavigationPage("edit-language");
//            $page->setParams(array('id' => $languageId));
            
            $languageForm = $this->getLanguageForm($language);
            $request = $this->getRequest();
            if ($request->isPost()) {
                return $this->storePostData($request->getPost(), $languageForm);
            } else {
                return $this->displayForm($languageForm, $languageId);
            }
        } else {
            $this->displayGenericErrorMessage();
        }
    }

    public function deleteAction()
    {
        $languageService = $this->getLanguageService();
        $languageId = $this->params()->fromRoute('id', 0);
        $language = $languageService->getLanguageById($languageId);

        if ($language) {
            $languageService->removeUserRelationships($languageId);
            $languageService->remove($language);
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Language has been deleted successfully.'));
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Language could not be deleted at this time.'), "error");
        }

        return $this->redirectTo('index');
    }

    /**
     * Get a language entity object
     * 
     * @param type $id <optional>
     * @return type
     */
    private function getLanguage($id)
    {
        $language = $this->getLanguageService()->getLanguageById($id);
        return $language;
    }

    /**
     * Get a new language entity object
     * 
     * @return type
     */
    private function getNewLanguage()
    {
        $language = new \Application\Entity\Language();
        return $language;
    }

    /**
     * Display Language form
     * 
     * @param type $languageForm
     * @return ViewModel a view
     */
    private function displayForm($languageForm, $languageId = 0)
    {
        $viewValues = array(
            'languageId' => $languageId,
            'form' => $languageForm
        );
        if ($languageId == 0) {
            $view = new ViewModel($viewValues);
            $view->setTemplate('application/language/edit.phtml');
            return $view;
        } else {
            return $viewValues;
        }
    }

    /**
     * Validate the post data, then store it
     * or return a validation message
     * 
     * @param type $post
     * @param type $languageForm
     * @return redirects or display the form
     */
    private function storePostData($post, $languageForm)
    {
        $languageForm->setData($post);
        $language = $languageForm->getObject();
        if ($languageForm->isValid()) {
            $this->saveLanguageData($language);
            return $this->redirectTo('index');
        } else {
            return $this->displayForm($languageForm, $language->getLanguageId());
        }
    }

    private function saveLanguageData($language)
    {
        $resultId = $this->getLanguageService()->persistData($language);
        if ($resultId > 0) {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Language has been successfully saved.'));
        } else {
            $this->sendTranslatedFlashMessage($this->getTranslator()->translate('Language could not be saved at this time.'), 'error');
        }
    }

    private function getLanguageForm($language)
    {
        $formFactory = $this->getFormFactory();
        $languageForm = $formFactory->createLanguageForm();
        $languageForm->bind($language);
        return $languageForm;
    }

    private function getLanguageService()
    {
        return $this->getRegisteredInstance(
                        'Application\Service\LanguageService');
    }

    private function redirectTo($action)
    {
        return $this->redirectToPath('language', $action);
    }

}