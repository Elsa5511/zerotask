<?php

namespace LadocDocumentation\Service;


use LadocDocumentation\Entity\LoadBasicInformation;

class LoadBasicInformationService extends BasicInformationService {

    protected function createNewBasicInformationInternal($documentation) {
        $loadBasicInformation = new LoadBasicInformation();
        $loadBasicInformation->setLadocDocumentation($documentation);
        return $loadBasicInformation;
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadBasicInformation');
    }

}
