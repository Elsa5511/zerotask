<?php

namespace Application\Controller\Helper;


use Application\Controller\AbstractBaseController;
use Application\Service\Helper\DeactivationServiceHelper;
use Application\Utility\ServiceMessageToFlashMessageConverter;

class DeactivationHelper {
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';

    private $controller;
    private $deactivationServiceHelper;

    public function __construct(AbstractBaseController $controller, $service, $translator) {
        $this->controller = $controller;
        $this->deactivationServiceHelper = new DeactivationServiceHelper($service, $translator);
    }

    public function deactivateAction($id) {
        return $this->changeActiveStatusAction($id, self::STATUS_INACTIVE);
    }

    public function activateAction($id) {
        return $this->changeActiveStatusAction($id, self::STATUS_ACTIVE);
    }

    private function changeActiveStatusAction($id, $newStatus) {
        if ($id > 0) {
            try {
                $serviceMessage = $this->deactivationServiceHelper->changeActiveStatus($id, $newStatus);
                if ($serviceMessage !== null) {
                    $flashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
                    $this->controller->sendFlashMessageFrom($flashMessage);
                    return true;
                }
            } catch (\Application\Service\ServiceOperationException $exception) {
                $this->controller->sendTranslatedFlashMessage($exception->getMessage(), 'error', true);
            }
        } else {
            $this->controller->displayGenericErrorMessage();
        }
        return false;
    }

    public function deactivateManyAction($ids) {
        $serviceMessageArray = $this->deactivationServiceHelper->deactivateByIds($ids);
        foreach ($serviceMessageArray as $serviceMessage) {
            $flashMessage = ServiceMessageToFlashMessageConverter::convert($serviceMessage);
            $this->controller->sendFlashMessageFrom($flashMessage);
        }
    }
}