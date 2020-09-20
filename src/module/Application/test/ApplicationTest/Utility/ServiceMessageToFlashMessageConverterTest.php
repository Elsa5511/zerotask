<?php

namespace ApplicationTest\Utility;

use PHPUnit_Framework_TestCase;
use Application\Utility\ServiceMessageToFlashMessageConverter;
use Application\Utility\ServiceMessage;
use Application\Utility\FlashMessage;

class ServiceMessageToFlashMessageConverterTest extends PHPUnit_Framework_TestCase {
    
    public function testCanConvertSuccessMessage() {
        $messageType = 'success';
        $message = 'Everything went ok.';
        $serviceMessage = new ServiceMessage($messageType, $message);
        $flashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
        $this->assertEquals($messageType, $flashMessage->getNamespace());
        $this->assertEquals($message, $flashMessage->getMessage());
    }
    
    public function testCanConvertErrorMessage() {
        $messageType = 'error';
        $message = 'Ouch! Something broke.';
        $serviceMessage = new ServiceMessage($messageType, $message);
        $flashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
        $this->assertEquals($messageType, $flashMessage->getNamespace());
        $this->assertEquals($message, $flashMessage->getMessage());
    }
    
    public function testConvertsToSuccessIfMessageTypeIsOtherThanError() {
        $message = 'Please choose an option.';
        $serviceMessage = new ServiceMessage('info', $message);
        $flashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
        $this->assertEquals('success', $flashMessage->getNamespace());
        $this->assertEquals($message, $flashMessage->getMessage());        
    }
}
