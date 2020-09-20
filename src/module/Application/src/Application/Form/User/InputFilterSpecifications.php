<?php

namespace Application\Form\User;

use Zend\Validator\EmailAddress;
use Application\Validator\PasswordMatcherValidator;
use Application\Form\AbstractBaseFieldset;

class InputFilterSpecifications {

    private $fieldset;

    public function __construct($fieldset) {
        $this->fieldset = $fieldset;
    }

    public function inputFilterPhoneNumber() {
        return array(
            'required' => false,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => array(
                $this->fieldset->getLengthValidator(4, 16)
            )
        );
    }

    public function inputFilterName() {
        return array(
            'required' => true,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => $this->getNameValidators()
        );
    }

    public function inputFilterEmployeeNo() {
        return array(
            'required' => false,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => array(
                $this->fieldset->getLengthValidator(1, 64)
            )
        );
    }

    public function inputFilterEmail() {
        return array(
            'required' => false,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => $this->getEmailValidators()
        );
    }

    public function inputFilterOldPassword($currentEncryptedPassword) {
        return array(
            'required' => false,
            'validators' => $this->getPasswordMatcherValidators($currentEncryptedPassword)
        );
    }

    public function inputFilterNewPassword() {
        return array(
            'required' => false,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => $this->getNewPasswordValidators(UserFields::FIELD_NEW_PASSWORD_VERIFICATION)
        );
    }

    public function inputFilterVerificationPassword() {
        return array(
            'required' => false,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => $this->getNewPasswordValidators(UserFields::FIELD_NEW_PASSWORD)
        );
    }

    public function inputFilterRequiredNewPassword() {
        $inputFilterNewPassword = $this->inputFilterNewPassword();
        $inputFilterNewPassword['required'] = true;
        return $inputFilterNewPassword;
    }

    public function inputFilterUsername() {
        return array(
            'required' => true,
            'filters' => $this->fieldset->getTextFilters(),
            'validators' => $this->getUsernameValidators()
        );
    }

    public function inputFilterRequired($isRequired) {
        return array(
            'required' => $isRequired
        );
    }

    public function inputFilterAccessibleApplications() {
        return array(
            'required' => true,
            'validators' => array(
                $this->fieldset->getNotEmptyValidator()
            )
        );
    }

    public function getPasswordMatcherValidators($currentEncryptedPassword) {
        $wrongOldPasswordMessage = $this->translate('Wrong password');
        return array(
            array(
                'name' => 'Application\Validator\PasswordMatcherValidator',
                'options' => array(
                    PasswordMatcherValidator::CURRENT_ENCRYPTED_PASSWORD_OPTION => $currentEncryptedPassword,
                    'messages' => array(
                        PasswordMatcherValidator::WRONG_PASSWORD => $wrongOldPasswordMessage
                    )
                )
            )
        );
    }

    private function getNewPasswordValidators($token) {
        $notSameMessage = $this->translate("The two passwords do not match");
        return array(
            $this->fieldset->getNotEmptyValidator(),
            $this->fieldset->getLengthValidator(AbstractBaseFieldset::PASSWORD_MIN_LENGTH, AbstractBaseFieldset::PASSWORD_MAX_LENGTH),
            array(
                'name' => 'Identical',
                'options' => array(
                    'token' => $token,
                    'messages' => array(
                        \Zend\Validator\Identical::NOT_SAME => $notSameMessage
                    )
                )
            ),
            $this->fieldset->getPasswordFormatValidator()
        );
    }

    private function getNameValidators() {
        return array(
            $this->fieldset->getNotEmptyValidator(),
            $this->fieldset->getLengthValidator(),
            $this->fieldset->getOnlyLettersValidator()
        );
    }

    private function getEmailValidators() {
        $objectNotUniqueMessage = $this->translate("This email is registered on another account");

        return array(
            $this->fieldset->getNotEmptyValidator(),
            $this->fieldset->getLengthValidator(4),
            $this->getEmailAddressValidator(),
            $this->getUniqueObjectValidator('email', $objectNotUniqueMessage)
        );
    }

    private function getUsernameValidators() {
        $objectNotUniqueMessage = $this->translate("This username is registered on another account");
        return array(
            $this->fieldset->getNotEmptyValidator(),
            $this->fieldset->getLengthValidator(4),
            $this->getUniqueObjectValidator('username', $objectNotUniqueMessage)
        );
    }

    private function getEmailAddressValidator() {
        $message = $this->translate("Invalid email address");
        return array(
            'name' => "EmailAddress",
            'options' => array(
                'domain' => false,
                'messages' => array(EmailAddress::INVALID => $message
                )
            )
        );
    }

    private function getUniqueObjectValidator($field, $objectNotUniqueMessage) {
        return array(
            'name' => 'DoctrineModule\Validator\UniqueObject',
            'options' => array(
                'object_manager' => $this->fieldset->getObjectManager(),
                'object_repository' => $this->fieldset->getObjectManager()
                        ->getRepository('Application\Entity\User'),
                'fields' => $field,
                'use_context' => true,
                'messages' => array(
                    'objectNotUnique' => $objectNotUniqueMessage
                )
            )
        );
    }

    private function translate($text) {
        return $this->fieldset->getTranslator()->translate($text);
    }

}
