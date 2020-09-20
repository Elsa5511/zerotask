<?php
namespace Equipment\Service;

use Sysco\Aurora\Doctrine\ORM\Service;

class CheckinService extends Service
{    
    private function getUserRepository()
    {
        return $this->getEntityManager()
                ->getRepository('Application\Entity\User');
    }

    public function persistData($checkin, $currentUserId) {
        $currentUser = $this->getUserRepository()->find($currentUserId);
        $checkin->setCheckedBy($currentUser);

        $this->updateEquipmentInstance($checkin);

        parent::persist($checkin);
        return $checkin->getCheckinId();
    }
    
    private function updateEquipmentInstance($checkin)
    {
        $equipmentInstance = $checkin->getEquipmentInstance();
        $equipmentInstance->setCheckedOut(false);
    }
}

