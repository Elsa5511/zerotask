<?php
namespace ApplicationTest\Utility;

use PHPUnit_Framework_TestCase;
use Application\Utility\FlashMessage;

class FlashMessageTest extends PHPUnit_Framework_TestCase {
    
    public function testCanCreateFlashMessage() {        
        $namespace = 'success';
        $message = 'Everything is ok.';
        $flashMessage = new FlashMessage($namespace, $message);
        $this->assertEquals($namespace, $flashMessage->getNamespace());
        $this->assertEquals($message, $flashMessage->getMessage());
    }

    public function testCanCreateAnotherFlashMessage() {        
        $namespace = 'error';
        $message = 'Something went wrong.';
        $flashMessage = new FlashMessage($namespace, $message);
        $this->assertEquals($namespace, $flashMessage->getNamespace());
        $this->assertEquals($message, $flashMessage->getMessage());
    }    
}
