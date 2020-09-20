<?php

namespace Equipment\Service;

use Sysco\Aurora\Doctrine\ORM\Service;

class CheckoutService extends Service
{    
    private function getCheckoutRepository()
    {
        return $this->getEntityManager()
                ->getRepository('Equipment\Entity\Checkout');
    }

    private function getEquipmentInstanceRepository()
    {
        return $this->getEntityManager()
                        ->getRepository('Equipment\Entity\EquipmentInstance');
    }

    private function getUserRepository()
    {
        return $this->getEntityManager()
                        ->getRepository('Application\Entity\User');
    }

    public function saveAll($checkout, $instanceIds, $currentUserId)
    {
        $currentDate = new \DateTime('NOW');
        $checkout->setCheckoutDate($currentDate);

        $currentUser = $this->getUserRepository()->find($currentUserId);
        $checkout->setCheckedBy($currentUser);

        $flashMessengerArray = array();
        foreach ($instanceIds as $instanceId) {
            $entity = clone $checkout;
            $flashMessengerArray[] = $this->saveOne($entity, $instanceId);
        }

        $this->getEntityManager()->flush();
        return $flashMessengerArray;
    }

    public function getCheckout($chekoutId)
    {
        return $this->getCheckoutRepository()->find($chekoutId);
    }

    
    private function saveOne($entityToSave, $equipmentInstanceId)
    {
        $namespace = "error";
        $equipmentInstance = $this->getEquipmentInstanceRepository()
                ->find($equipmentInstanceId);
        if ($equipmentInstance) {
            $isAlreadyCheckedOut = $equipmentInstance->isCheckedOut();
            if ($isAlreadyCheckedOut) {
                $format = $this->translate('"%s" is already checked out');
            } else {
                $this->updateEquipmentInstance($equipmentInstance);
                $entityToSave->setEquipmentInstance($equipmentInstance);
                $this->getEntityManager()->persist($entityToSave);
                $namespace = "success";
                $format = $this->translate('"%s" has been updated successfully');
            }
            $message = sprintf($format, $equipmentInstance->getSerialNumber());
        } else {
            $message = $this->translate('Equipment Instance doesn\'t exist');
        }

        return array(
            'namespace' => $namespace,
            'message' => $message
        );
    }

    private function updateEquipmentInstance($equipmentInstance)
    {
        $equipmentInstance->setCheckedOut(true);
        $this->getEntityManager()->persist($equipmentInstance);
    }

    public function getLastCheckout($equipmentInstanceId)
    {
        return $this->getCheckoutRepository()->findOneBy(
                    array('equipmentInstance' => $equipmentInstanceId), 
                    array('checkoutId'=>'DESC')
               );
    }
}

