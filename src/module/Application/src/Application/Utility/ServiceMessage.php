<?php

namespace Application\Utility;

class ServiceMessage {
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    
    private $messageType;
    private $message;
    
    /**
     * 
     * @param string $messageType success | error
     * @param string $mesasage The message to display to the user
     */
    public function __construct($messageType, $mesasage) {
        $this->messageType = $messageType;
        $this->message = $mesasage;
    }

    public function getMessageType() {
        return $this->messageType;
    }

    public function getMessage() {
        return $this->message;
    }
}
