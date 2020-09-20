<?php

namespace Application\Service\Helper;

use Application\Service\EntityDoesNotExistException;
use Application\Service\ServiceOperationException;
use Application\Utility\ServiceMessage;

class DeactivationServiceHelper {

    private $service;
    private $translator;

    public function __construct($service, $translator) {
        $this->service = $service;
        $this->translator = $translator;
    }

    public function changeActiveStatus($id, $newStatus) {
        $entity = $this->service->findById($id);
        if (!$entity) {
            throw new EntityDoesNotExistException(
                $this->translator->translate("Entity doesn't exist"));
        }

        if ($entity->getActiveStatus() !== $newStatus) {
            $entity->setActiveStatus($newStatus);
            $this->service->persist($entity);
            if ($newStatus === 'active') {
                $successMessage = $this->translator->translate('"%s" was set to active.');
            }
            else {
                $successMessage = $this->translator->translate('"%s" was set to inactive.');
            }
            $message = sprintf($successMessage, $entity, $this->translator->translate($newStatus));
            return new ServiceMessage('success', $message);
        }
        return null;
    }

    public function deactivateByIds($ids) {
        $serviceMessageArray = array();
        foreach ($ids as $id) {
            try {
                $serviceMessage = $this->changeActiveStatus($id, 'inactive');
            } catch (ServiceOperationException $exception) {
                $serviceMessage = new ServiceMessage('error', $exception->getMessage());
            }
            if ($serviceMessage !== null) {
                array_push($serviceMessageArray, $serviceMessage);
            }
        }
        return $serviceMessageArray;
    }

}