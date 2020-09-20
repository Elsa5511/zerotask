<?php

namespace ApplicationTest\Utility;

use PHPUnit_Framework_TestCase;
use Application\Utility\ServiceMessage;

class ServiceMessageTest extends PHPUnit_Framework_TestCase {
    
    public function testCanInitialize() {
        $this->assertNotNull(new ServiceMessage('', ''));
    }
    
    public function testCanCreateSuccessMessage() {
        $messageType = 'success';
        $successMessage = 'Everything went as expected.';
        $this->canCreateMessage($messageType, $successMessage);
        }
    
    public function testCanCreateErrorMessage() {
        $messageType = 'error';
        $errorMessage = 'Oh no! Something broke.';
        $this->canCreateMessage($messageType, $errorMessage);
    }
    
    private function canCreateMessage($messageType, $message) {
        $serviceMessage = new ServiceMessage($messageType, $message);
        $this->assertEquals($messageType, $serviceMessage->getMessageType());
        $this->assertEquals($message, $serviceMessage->getMessage());
    }    
}
