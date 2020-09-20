<?php

namespace Application\Form\User;

use Application\Form\AbstractBaseFieldset;
use Application\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UserFieldset extends AbstractBaseFieldset {

//    private $isNewUser;
//    private $showAdminFields;
    private $currentEncryptedPassword;
    private $fieldDisplayOptions;

    public function __construct(ObjectManager $objectManager, FieldDisplayOptions $fieldDisplayOptions, $currentEncryptedPassword) {
        parent::__construct('user');
        $this->fieldDisplayOptions = $fieldDisplayOptions;
//        $this->isNewUser = $isNewUser;
//        $this->showAdminFields = $showAdminFields;
        $this->currentEncryptedPassword = $currentEncryptedPassword;
        $this->setObjectManager($objectManager);
        $hydrator = new DoctrineHydrator($objectManager, 'Application\Entity\User', false);
        $this->setHydrator($hydrator);
        $this->setObject(new User());
        $this->addFields();
    }

    private function addFields() {
        $userFields = new UserFields($this->getObjectManager(), $this->getTranslator());
        $this->add($userFields->fieldUserId());
        $this->add($userFields->fieldFirstName());
        $this->add($userFields->fieldLastName());
        $this->add($userFields->fieldEmployeeNo());
        if ($this->fieldDisplayOptions->getShowAdminFields()) {
            $this->add($userFields->fieldOrganization());

            if($this->fieldDisplayOptions->getShowAdminFieldsForVedos())
            {
                $this->add($userFields->fieldOrganizationRestrictionEnabled());
            }
        }
        $this->add($userFields->fieldEmailWork());
        $this->add($userFields->fieldEmailPrivate());
        if ($this->fieldDisplayOptions->getShowNewUserFields()) {
            $this->add($userFields->fieldEditableUsername());
        } else {
            $this->add($userFields->fieldReadOnlyUsername());
        }
        if ($this->fieldDisplayOptions->getShowModifyOwnAccountFields() || $this->fieldDisplayOptions->getShowAdminFields()) {
            $this->add($userFields->fieldOldPassword());
            $this->add($userFields->fieldNewPassword());
        } else if ($this->fieldDisplayOptions->getShowNewUserFields()) {
            $this->add($userFields->fieldPassword());
        }
        if ($this->fieldDisplayOptions->getShowModifyOwnAccountFields() ||
                $this->fieldDisplayOptions->getShowNewUserFields()) {
            $this->add($userFields->fieldVerifyNewPassword());
        }

        $this->add($userFields->fieldPhoneNumberMobile());
        $this->add($userFields->fieldPhoneNumberOther());
        $this->add($userFields->fieldLanguage());
        if ($this->fieldDisplayOptions->getShowAdminFields()) {
            $this->add($userFields->fieldSuperior());
            if ($this->fieldDisplayOptions->getShowModifyOwnAccountFields())
            {
                $this->add($userFields->fieldReadOnlyRole());
            }
            else{
                $this->add($userFields->fieldRole());
            }
            $this->add($userFields->fieldCompetenceAreas());
            $this->add($userFields->fieldApplicationAccess());
        }
    }

    public function getInputFilterSpecification() {
        $inputFilterSpesifications = new InputFilterSpecifications($this);
        $inputFilter = array();
        $inputFilter[UserFields::FIELD_FIRST_NAME] = $inputFilterSpesifications->inputFilterName();
        $inputFilter[UserFields::FIELD_LAST_NAME] = $inputFilterSpesifications->inputFilterName();
        $inputFilter[UserFields::FIELD_EMPLOYEE_NO] = $inputFilterSpesifications->inputFilterEmployeeNo();
        if ($this->fieldDisplayOptions->getShowAdminFields()) {
            $inputFilter[UserFields::FIELD_ORGANIZATION] = $inputFilterSpesifications->inputFilterRequired(false);
        }
        $inputFilter[UserFields::FIELD_EMAIL_WORK] = $inputFilterSpesifications->inputFilterEmail();
        $inputFilter[UserFields::FIELD_EMAIL_PRIVATE] = $inputFilterSpesifications->inputFilterEmail();
        if ($this->fieldDisplayOptions->getShowNewUserFields()) {
            $inputFilter[UserFields::FIELD_USERNAME] = $inputFilterSpesifications->inputFilterUsername();
        }
        if ($this->fieldDisplayOptions->getShowModifyOwnAccountFields()) {
            $inputFilter[UserFields::FIELD_OLD_PASSWORD] = $inputFilterSpesifications->inputFilterOldPassword($this->currentEncryptedPassword);
            $inputFilter[UserFields::FIELD_NEW_PASSWORD] = $inputFilterSpesifications->inputFilterNewPassword();
        } else if ($this->fieldDisplayOptions->getShowNewUserFields()) {
            $inputFilter[UserFields::FIELD_NEW_PASSWORD] = $inputFilterSpesifications->inputFilterNewPassword();
        }
        if ($this->fieldDisplayOptions->getShowModifyOwnAccountFields() ||
                $this->fieldDisplayOptions->getShowNewUserFields()) {
            $inputFilter[UserFields::FIELD_NEW_PASSWORD_VERIFICATION] = $inputFilterSpesifications->inputFilterVerificationPassword();
        }
        $inputFilter[UserFields::FIELD_NEW_PASSWORD_VERIFICATION] = $inputFilterSpesifications->inputFilterVerificationPassword();
        $inputFilter[UserFields::FIELD_PHONE_NUMBER_MOBILE] = $inputFilterSpesifications->inputFilterPhoneNumber();
        $inputFilter[UserFields::FIELD_PHONE_NUMBER_OTHER] = $inputFilterSpesifications->inputFilterPhoneNumber();
        if ($this->fieldDisplayOptions->getShowAdminFields()) {
            $inputFilter[UserFields::FIELD_SUPERIOR] = $inputFilterSpesifications->inputFilterRequired(false);
            $inputFilter[UserFields::FIELD_COMPETENCE_AREAS] = $inputFilterSpesifications->inputFilterRequired(false);
            $inputFilter[UserFields::FIELD_ACCESSIBLE_APPLICATIONS] = $inputFilterSpesifications->inputFilterAccessibleApplications();
        }

        return $inputFilter;
    }

}
