<?php

namespace LadocDocumentation\Service;

use Application\Service\AbstractBaseService;
use LadocDocumentation\Entity\LadocRestraintNonCertified;

class RestraintNonCertifiedService extends AbstractBaseService {

    public function findByDocumentation($documentationId, $type) {
        if($type == 'load')
            return $this->getEntityRepository()->findBy(array('loadDocumentation' => $documentationId));
        else
            return $this->getEntityRepository()->findBy(array('carrierDocumentation' => $documentationId));
    }

    public function getNewEntity(\LadocDocumentation\Entity\LadocDocumentation $ladocDocumentation)
    {
        $ladocRestraintNonCertified = new LadocRestraintNonCertified();

        $ladocRestraintNonCertified->setLadocDocumentationWithTypeChecked($ladocDocumentation);

        return $ladocRestraintNonCertified;
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocRestraintNonCertified');
    }
}