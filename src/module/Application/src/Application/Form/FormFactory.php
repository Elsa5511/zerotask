<?php

namespace Application\Form;
use Application\Entity\AttachmentWithLink;

/**
 * Form factory class
 * Use its methods to create forms
 */
class FormFactory extends AbstractFormFactory {

    public function createUserFormForNewUser($showAdminFieldsForVedos = false) {
        $showNewUserFields = true;
        $showAdminFields = true;
        $showModifyOwnAccountFields = false;
        $userFieldDisplayOptions = new User\FieldDisplayOptions($showNewUserFields, $showAdminFields, $showModifyOwnAccountFields, $showAdminFieldsForVedos);
        return $this->createUserForm($userFieldDisplayOptions);
    }

    public function createUserForm($userFieldDisplayOptions, $currentEncryptedPassword = 'n/a') {
        $form = $this->getNewForm('user');
        $objectManager = $this->getObjectManager();
        $fieldset = new User\UserFieldset($objectManager, $userFieldDisplayOptions, $currentEncryptedPassword);
        $this->setupFieldset($fieldset, 'Application\Entity\User');
        $form->add($fieldset);
        $form->add(
                array(
                    'name' => 'submit',
                    'type' => 'submit',
                    'attributes' => array(
                        'id' => 'user-btn-submit',
                        'value' => $this->getTranslator()->translate('Save changes'),
                        'class' => 'btn btn-primary'
                    )
        ));
        return $form;
    }

    /**
     * Creates a Attachment form
     * Returns an instance of Form
     *
     * @param string $entityPath 
     * @param string $mode
     * @return object Form
     */
    public function createAttachmentForm($entityPath, $mode) {
        $attachmentFieldset = new AttachmentFieldset($this->getObjectManager(), $mode);
        return $this->createAttachmentFormInternal($entityPath, $attachmentFieldset);
    }

    public function createAttachmentWithLinkForm($entityPath, $mode) {
        $attachmentFieldset = new AttachmentWithLinkFieldset($this->getObjectManager(), $mode);
        return $this->createAttachmentFormInternal($entityPath, $attachmentFieldset);
    }

    private function createAttachmentFormInternal($entityPath, $attachmentFieldset) {
        $form = $this->getNewForm('attachment-form');
        $this->setupFieldset($attachmentFieldset, $entityPath);
        $form->add($attachmentFieldset);

        return $form;
    }

    /**
     * Creates a Section form
     *
     * @param string $entityPath
     * @param array $parentOptions
     * @param string $mode
     * @return Form Object
     */
    public function createSectionForm($entityPath, $parentOptions, $mode) {
        $form = $this->getNewForm('section-form');
        $sectionFieldset = new SectionFieldset($this->getObjectManager(), $entityPath, $parentOptions, $mode);
        $sectionFieldset->setTranslator($this->getTranslator());
        $sectionFieldset->setUseAsBaseFieldset(true);
        $form->add($sectionFieldset);

        return $form;
    }

    /**
     * Creates a Forgot Password form
     * Returns an instance of Form
     */
    public function createForgotPasswordForm() {
        $form = $this->getNewForm('user');

        // The fieldset will hydrate an object entity
        $hydrator = $this->getHydratorForm('Application\Entity\User');
        $form->setHydrator($hydrator);

        // Add the user fieldset, and set it as the base fieldset        
        $forgotPasswordFieldset = new ForgotPasswordFieldset($this->getObjectManager());
        $forgotPasswordFieldset->setUseAsBaseFieldset(true);
        $form->add($forgotPasswordFieldset);

        return $form;
    }

    /**
     * Creates a language form
     * Returns an instance of Form
     */
    public function createLanguageForm() {
        $languageFieldset = $this->getLanguageFieldset();
        $form = $this->getNewForm('language');
        $form->add($languageFieldset);
        return $form;
    }

    /**
     * Get language fieldset
     * @return \Application\Form\LanguageFieldset
     */
    private function getLanguageFieldset() {
        $languageFieldset = new LanguageFieldset($this->getObjectManager());
        $this->setupFieldset($languageFieldset, 'Application\Entity\Language');
        return $languageFieldset;
    }

    /**
     * Creates an roloe form
     * Returns an instance of Form
     */
    public function createRoleForm($roleId) {
        $roleFieldset = $this->getRoleFieldset($roleId);
        $form = $this->getNewForm('role');
        $form->add($roleFieldset);
        return $form;
    }

    /**
     * Get role fieldset
     * @return \Application\Form\RoleFieldset
     */
    private function getRoleFieldset($roleId) {
        $roleFieldset = new RoleFieldset(array(
            'role_id' => $roleId,
            'object_manager' => $this->getObjectManager()
        ));
        $this->setupFieldset($roleFieldset, 'Application\Entity\Role');
        return $roleFieldset;
    }

    /**
     * Creates an organization form
     * Returns an instance of Form
     */
    public function createOrganizationForm() {
        $organizationFieldset = $this->getOrganizationFieldset();
        $form = $this->getNewForm('organization');
        $form->add($organizationFieldset);
        return $form;
    }

    /**
     * Get organization fieldset
     * @return \Application\Form\OrganizationFieldset
     */
    private function getOrganizationFieldset() {
        $organizationFieldset = new OrganizationFieldset($this->getObjectManager());
        $this->setupFieldset($organizationFieldset, 'Application\Entity\Organization');
        return $organizationFieldset;
    }

    /**
     * Creates a Location form
     * Returns an instance of Form
     */
    public function createLocationForm() {
        $locationFieldset = $this->getLocationFieldset();
        $form = $this->getNewForm('location');
        $form->add($locationFieldset);
        return $form;
    }

    /**
     * Get location fieldset
     * @return \Application\Form\LocationFieldset
     */
    private function getLocationFieldset() {
        $locationFieldset = new LocationFieldset($this->getObjectManager());
        $this->setupFieldset($locationFieldset, 'Application\Entity\LocationTaxonomy');
        return $locationFieldset;
    }

}
