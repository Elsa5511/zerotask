<?php

namespace Application\Form;

use Sysco\Aurora\Form\Fieldset;

class AbstractBaseFieldset extends Fieldset
{

    const DEFAULT_ENCODING = "UTF-8";
    const REGEX_ONLY_LETTERS_NUMBERS = "/^[a-zA-Z0-9']+$/u";
    const REGEX_LETTERS_NUMBERS = "/^([ \x{00C0}-\x{01FF}a-zA-Z0-9'\-])+$/u";
    const REGEX_ONLY_LETTERS = "/^([ \x{00C0}-\x{01FF}a-zA-Z'\-])+$/u";
    const REGEX_PASSWORD = "^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$";
    const REGEX_URL = "/^[-a-zA-Z0-9@:%_\+.~\#?&\/\/=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~\#?&\/\/=]*)?$/si";
    const PASSWORD_MIN_LENGTH = 6;
    const PASSWORD_MAX_LENGTH = 16;

    private $objectManager;
    private $translator;

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    public function setObjectManager($value)
    {
        $this->objectManager = $value;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    public function setTranslator($value)
    {
        $this->translator = $value;
        return $this;
    }

    public function getTextFilters()
    {
        return array(
            array(
                'name' => 'StripTags'
            ),
            array(
                'name' => 'StringTrim'
            )
        );
    }

    public function getDateValidator()
    {
        $invalidMessage = $this->getTranslator()->translate("Invalid date");
        $falseFormatMessage = $this->getTranslator()->translate("Invalid date format");
        return array(
            'name' => 'Date',
            'options' => array(
                'messages' => array(
                    \Zend\Validator\Date::INVALID => $invalidMessage,
                    \Zend\Validator\Date::INVALID_DATE => $invalidMessage,
                    \Zend\Validator\Date::FALSEFORMAT => $falseFormatMessage,
                ),
            ),
        );
    }

    public function getNotEmptyValidator()
    {
        $message = $this->getTranslator()->translate("Value is required");
        return array(
            'name' => 'NotEmpty',
            'encoding' => self::DEFAULT_ENCODING,
            'options' => array(
                'messages' => array(
                    \Zend\Validator\NotEmpty::IS_EMPTY => $message
                )
            )
        );
    }

    public function getBetweenValuesValidator($min = 0, $max = 100)
    {
        // Setting up validation messages
        $betweenFormat = $this->getTranslator()->translate(
                'The input should be between %d and %d');
        $betweenMessage = sprintf($betweenFormat, $min, $max);

        return array(
            'name' => "Zend\Validator\Between",
            'options' => array(
                'encoding' => self::DEFAULT_ENCODING,
                'min' => $min,
                'max' => $max,
                'inclusive' => true,
                'messages' => array(
                    \Zend\Validator\Between::NOT_BETWEEN => $betweenMessage
                )
            )
        );
    }

    public function getNumericalValuesValidator()
    {
        // Setting up validation messages
        $message = $this->getTranslator()->translate(
                'The input should be a number');

        return array(
            'name' => "Zend\I18n\Validator\IsFloat",
            'options' => array(
                'encoding' => self::DEFAULT_ENCODING,
                'messages' => array(
                    \Zend\I18n\Validator\IsFloat::NOT_FLOAT => $message
                )
            )
        );
    }
    public function getOnlyDigitsValidator()
    {
        $message = $this->getTranslator()->translate(
                "The value has to contain only digits");
        return array(
            'name' => 'Digits',
            'encoding' => self::DEFAULT_ENCODING,
            'options' => array(
                'messages' => array(
                    \Zend\Validator\Digits::NOT_DIGITS => $message
                )
            )
        );
    }

    public function getOnlyLettersValidator()
    {
        $message = $this->getTranslator()->translate(
                'This input contains invalid characters. Only letters are allowed.');
        $validation = array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => self::REGEX_ONLY_LETTERS,
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH => $message
                )
            )
        );
        return $validation;
    }

    /**
     * 
     * @return array validator
     */
    public function getOnlyLettersNumbersValidator()
    {
        $message = $this->getTranslator()->translate(
                'This input contains invalid characters. Only letters and numbers are allowed.');

        return array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => self::REGEX_LETTERS_NUMBERS,
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH => $message
                )
            )
        );
    }

    /**
     * 
     * @param type $min
     * @param type $max
     * @return array validator
     */
    public function getLengthValidator($min = 1, $max = 100)
    {

        // Setting up validation messages
        $tooShortFormat = $this->getTranslator()->translate(
                'The input is less than %d character(s) long');
        $tooShortMessage = sprintf($tooShortFormat, $min);

        $tooLongFormat = $this->getTranslator()->translate(
                'The input is more than %d characters long');
        $tooLongMessage = sprintf($tooLongFormat, $max);

        return array(
            'name' => 'StringLength',
            'options' => array(
                'encoding' => self::DEFAULT_ENCODING,
                'min' => $min,
                'max' => $max,
                'messages' => array(
                    \Zend\Validator\StringLength::TOO_SHORT => $tooShortMessage,
                    \Zend\Validator\StringLength::TOO_LONG => $tooLongMessage
                )
            )
        );
    }

    /**
     * 
     * @return array validator
     */
    public function getEmailValidator()
    {
        $message = $this->getTranslator()->translate(
                "The input is not a valid email address");
        return array(
            'name' => "EmailAddress",
            'options' => array(
                'domain' => false,
                'messages' => array(
                    \Zend\Validator\EmailAddress::INVALID => $message
                )
            )
        );
    }

    public function getMaxSizeFileValidator($size)
    {
        return array(
            'name' => '\Zend\Validator\File\Size',
            'options' => array(
                'max' => $size
            ),
            'messages' => array(
                \Zend\Validator\File\Size::TOO_BIG =>
                $this->getTranslator()->translate("The maximum weight per file must be $size"),
            )
        );
    }

    public function getPasswordFormatValidator()
    {
        $message = $this->getTranslator()->translate(
                'Password must include at least one upper case letter, one lower case letter, and one numeric digit.');
        return array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => "/" . self::REGEX_PASSWORD . "/",
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH => $message
                )
            )
        );
    }

    public function getImageFileValidator()
    {
        $wrongTypeMessageFormat = $this->getTranslator()->translate("The file must be of type %s");
        $wrongTypeMessage = sprintf($wrongTypeMessageFormat, "(jpg, jpeg, gif, png)");

        return array(
            'name' => '\Zend\Validator\File\MimeType',
            'options' => array(
                'mimeType' => array(
                    'image/jpg',
                    'image/gif',
                    'image/png',
                    'image/jpeg'
                ),
                'messages' => array(
                    \Zend\Validator\File\MimeType::FALSE_TYPE =>
                    $wrongTypeMessage,
                    \Zend\Validator\File\MimeType::NOT_DETECTED =>
                    $this->getTranslator()->translate('The file was not detected'),
                )
            ),
        );
    }

    public function getUrlValidator()
    {
        $message = $this->getTranslator()->translate(
            'The url is invalid.');

        return array(
            'name' => 'Regex',
            'options' => array(
                'pattern' => self::REGEX_URL,
                'messages' => array(
                    \Zend\Validator\Regex::NOT_MATCH => $message
                )
            )
        );
    }

}

