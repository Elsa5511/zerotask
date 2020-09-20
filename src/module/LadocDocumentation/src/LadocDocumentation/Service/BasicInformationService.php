<?php

namespace LadocDocumentation\Service;

use Acl\Repository\EntityRepository;
use Application\Service\AbstractBaseService;
use Application\Service\EntityDoesNotExistException;

abstract class BasicInformationService extends AbstractBaseService {

    /**
     * @return EntityRepository
     */
    protected abstract function getEntityRepository();
    protected abstract function createNewBasicInformationInternal($documentation);

    public function findByDocumentationId($documentationId) {
        $ladocDocumentation = $this->getEntityRepository()
            ->findBy(array('ladocDocumentation' => $documentationId));
        if (empty($ladocDocumentation)) {
            return null;
        }
        else {
            return $ladocDocumentation[0];
        }
    }

    public function createNewBasicInformation($documentationId) {
        $documentation = $this->getRepository('LadocDocumentation\Entity\LadocDocumentation')->find($documentationId);
        if ($documentation) {
            return $this->createNewBasicInformationInternal($documentation);
        }
        else {
            throw new EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }
    }
}