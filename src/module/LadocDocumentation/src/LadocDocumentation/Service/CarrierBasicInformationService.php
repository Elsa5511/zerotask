<?php

namespace LadocDocumentation\Service;

use LadocDocumentation\Entity\CarrierBasicInformation;

class CarrierBasicInformationService extends BasicInformationService {

    protected function createNewBasicInformationInternal($documentation) {
        $carrierBasicInformation = new CarrierBasicInformation();
        $carrierBasicInformation->setLadocDocumentation($documentation);
        return $carrierBasicInformation;
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierBasicInformation');
    }
}
