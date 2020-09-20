<?php

namespace Application\Utility;

class FlashMessage {

    private $namespace;
    private $message;

    public function __construct($namespace, $message) {
        $this->namespace = $namespace;
        $this->message = $message;
    }

    public function getNamespace() {
        return $this->namespace;
    }

    public function getMessage() {
        return $this->message;
    }

}
