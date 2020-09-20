<?php

namespace Application\Utility;

use Application\Utility\FlashMessage;

class ServiceMessageToFlashMessageConverter {

    /**
     * Converts a service message to a flash message.
     * 
     * @param \Application\Service\ServiceMessage $serviceMessage
     * @return \Application\Utility\FlashMessage
     */
    public static function convert($serviceMessage) {
        return new FlashMessage(
                self::convertToSuccessOrError($serviceMessage->getMessageType()), $serviceMessage->getMessage()
        );
    }

    private static function convertToSuccessOrError($messageType) {
        if ($messageType === 'error') {
            return 'error';
        }
        return 'success';
    }

}
