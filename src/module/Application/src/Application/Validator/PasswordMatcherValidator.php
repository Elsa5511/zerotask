<?php

namespace Application\Validator;

use Zend\Crypt\Password\Bcrypt;
use Zend\Validator\AbstractValidator;

class PasswordMatcherValidator extends AbstractValidator {
    const CURRENT_ENCRYPTED_PASSWORD_OPTION = 'current-encrypted-password';
    const WRONG_PASSWORD = 'wrong-password';
    
    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function isValid($value) {
        $bcrypt = new Bcrypt();
        if ($bcrypt->verify($value, $this->getOption(PasswordMatcherValidator::CURRENT_ENCRYPTED_PASSWORD_OPTION))) {
            return true;
        }
        else {
            $this->error(self::WRONG_PASSWORD);
            return false;
        }
    }

    protected $messageTemplates = array(
        self::WRONG_PASSWORD => 'Wrong password'
    );

}
