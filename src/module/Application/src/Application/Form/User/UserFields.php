<?php

namespace Application\Form\User;

use Application\Form\AbstractBaseFieldset;

class UserFields {

    const FIELD_FIRST_NAME = 'firstName';
    const FIELD_LAST_NAME = 'lastName';
    const FIELD_EMPLOYEE_NO = 'employeeNo';
    const FIELD_ORGANIZATION = 'organizationId';
    const FIELD_EMAIL_WORK = 'email';
    const FIELD_EMAIL_PRIVATE = 'emailPrivate';
    const FIELD_USERNAME = 'username';
    const FIELD_OLD_PASSWORD = 'oldPassword';
    const FIELD_NEW_PASSWORD = 'password';
    const FIELD_NEW_PASSWORD_VERIFICATION = 'newPasswordVerification';
    const FIELD_PHONE_NUMBER_MOBILE = 'phoneNumberMobile';
    const FIELD_PHONE_NUMBER_OTHER = 'phoneNumberOther';
    const FIELD_LANGUAGE = 'languageId';
    const FIELD_SUPERIOR = 'superiorId';
    const FIELD_ROLE = 'role-id';
    const FIELD_COMPETENCE_AREAS = 'competenceAreas';
    const FIELD_ACCESSIBLE_APPLICATIONS = 'accessibleApplications';
    const FIELD_ORGANIZATION_RESTRICTION = 'organizationRestrictionEnabled';

    private $objectManager;

    public function __construct($objectManager) {
        $this->objectManager = $objectManager;
    }

    public function fieldUserId() {
        return array(
            'name' => 'userId',
            'type' => 'hidden'
        );
    }

    public function fieldFirstName() {
        return array(
            'name' => UserFields::FIELD_FIRST_NAME,
            'type' => 'text',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('First name')
            )
        );
    }

    public function fieldLastName() {
        return array(
            'name' => UserFields::FIELD_LAST_NAME,
            'type' => 'text',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Last name')
            )
        );
    }

    public function fieldEmployeeNo() {
        return array(
            'name' => UserFields::FIELD_EMPLOYEE_NO,
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Employee No.')
            )
        );
    }

    public function fieldOrganization() {
        return array(
            'name' => UserFields::FIELD_ORGANIZATION,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'id' => 'organization-id'
            ),
            'options' => array(
                'label' => $this->translate('Organization'),
                'empty_option' => $this->translate('Choose an organization'),
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\Organization',
                'property' => 'name',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('status' => 'active'),
                        'orderBy' => array(
                            'name' => 'ASC'
                        )
                    )
                )
            )
        );
    }

    public function fieldEmailWork() {
        return array(
            'name' => UserFields::FIELD_EMAIL_WORK,
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'required' => 'true'
            ),
            'options' => array(
                'label' => $this->translate('Email (work)')
            )
        );
    }

    public function fieldEmailPrivate() {
        return array(
            'name' => UserFields::FIELD_EMAIL_PRIVATE,
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => $this->translate('Email (private)')
            )
        );
    }

    public function fieldEditableUsername() {
        return array(
            'name' => UserFields::FIELD_USERNAME,
            'type' => 'text',
            'attributes' => array(
                'required' => 'true',
            ),
            'options' => array(
                'label' => $this->translate('Username')
            )
        );
    }

    public function fieldReadOnlyUsername() {
        $userNameField = $this->fieldEditableUsername();
        $userNameField['attributes']['readonly'] = 'readonly';
        return $userNameField;
    }

    public function fieldOldPassword() {
        return array(
            'name' => UserFields::FIELD_OLD_PASSWORD,
            'type' => 'password',
            'attributes' => array(
                'id' => UserFields::FIELD_OLD_PASSWORD,
                'autocomplete' => 'off',
            ),
            'options' => array(
                'label' => $this->translate('Old password')
            )
        );
    }

    public function fieldNewPassword($label = null) {
        if ($label === null) {
            $label = $this->translate("New password");
        }

        $passwordRequirementText = $this->translate(
                'Password must be at least 6 characters, no more than 16 characters, '
                . 'and must include at least one upper case letter, one lower case letter, '
                . 'and one numeric digit.'
        );
        return array(
            'name' => UserFields::FIELD_NEW_PASSWORD,
            'type' => 'password',
            'attributes' => array(
                'id' => 'password',
                'autocomplete' => 'off',
                'pattern' => AbstractBaseFieldset::REGEX_PASSWORD,
                'title' => $passwordRequirementText
            ),
            'options' => array(
                'label' => $label
            )
        );
    }

    public function fieldPassword() {
        return $this->fieldNewPassword($this->translate('Password'));
    }

    public function fieldVerifyNewPassword() {
        return array(
            'name' => UserFields::FIELD_NEW_PASSWORD_VERIFICATION,
            'type' => 'password',
            'attributes' => array(
                'id' => 'verify-password',
                'disabled' => 'disabled',
                'pattern' => AbstractBaseFieldset::REGEX_PASSWORD,
            ),
            'options' => array(
                'label' => $this->translate('Confirm your password')
            )
        );
    }

    public function fieldPhoneNumberMobile() {
        return array(
            'name' => UserFields::FIELD_PHONE_NUMBER_MOBILE,
            'type' => 'text',
            'attributes' => array(
                'type' => 'tel'
            ),
            'options' => array(
                'label' => $this->translate('Phone Number (mobile)')
            )
        );
    }

    public function fieldPhoneNumberOther() {
        return array(
            'name' => UserFields::FIELD_PHONE_NUMBER_OTHER,
            'type' => 'text',
            'options' => array(
                'label' => $this->translate('Phone Number (other)')
            )
        );
    }

    public function fieldLanguage() {
        return array(
            'name' => UserFields::FIELD_LANGUAGE,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'id' => 'language-id'
            ),
            'options' => array(
                'label' => $this->translate('Language'),
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\Language',
                'property' => 'name',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('status' => 'active'),
                        'orderBy' => array(
                            'name' => 'ASC'
                        )
                    )
                )
            )
        );
    }

    public function fieldSuperior() {
        return array(
            'name' => UserFields::FIELD_SUPERIOR,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'id' => 'superior-id'
            ),
            'options' => array(
                'label' => $this->translate('Superior'),
                'empty_option' => $this->translate('Choose a superior'),
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\User',
                'property' => 'displayName',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array(
                            'state' => 1
                        ),
                        'orderBy' => array(
                            'displayName' => 'ASC'
                        )
                    )
                )
            )
        );
    }

    public function fieldRole() {
        return array(
            'name' => UserFields::FIELD_ROLE,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'id' => 'role-id',
                'required' => 'required'
            ),
            'options' => array(
                'label' => $this->translate('Role'),
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\Role',
                'property' => 'name',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getAllButGuest',
                ),
            )
        );
    }

    public function fieldReadOnlyRole() {
//        $fieldRole = $this->fieldRole();
//        $fieldRole['attributes']['readonly'] = true;
//        return $fieldRole;
        return array(
            'name' => UserFields::FIELD_ROLE,
            'type' => 'text',
            'attributes' => array(
                'id' => 'role-id',
                'required' => 'required',
                'readonly' => true
            ),
            'options' => array(
                'label' => $this->translate('Role'),
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\Role',
                'property' => 'name',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'getAllButGuest',
                ),
            )
        );
    }

    public function fieldCompetenceAreas() {
        return array(
            'name' => UserFields::FIELD_COMPETENCE_AREAS,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'multiple' => 'multiple',
                'id' => 'competence_area_taxonomy_id',
            ),
            'options' => array(
                'object_manager' => $this->objectManager,
                'target_class' => 'Equipment\Entity\CompetenceAreaTaxonomy',
                'label' => $this->translate('Competence areas'),
            ),
        );
    }

    public function fieldApplicationAccess() {
        return array(
            'name' => UserFields::FIELD_ACCESSIBLE_APPLICATIONS,
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'multiple' => 'multiple',
                'id' => 'application_id',
                'required' => true
            ),
            'options' => array(
                'object_manager' => $this->objectManager,
                'target_class' => 'Application\Entity\ApplicationDescription',
                'label' => $this->translate('Application access'),
            ),
        );
    }

    public function fieldOrganizationRestrictionEnabled() {
        return array(
            'name' => UserFields::FIELD_ORGANIZATION_RESTRICTION,
            'type' => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'label' => $this->translate('Filter instances by organization'),
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        );
    }

    /**
     * Text is not translated here, but it makes poedit recognize the strings.
     * They actual translation is performed at a later time.
     */
    private function translate($text) {
        return $text;
    }

}
