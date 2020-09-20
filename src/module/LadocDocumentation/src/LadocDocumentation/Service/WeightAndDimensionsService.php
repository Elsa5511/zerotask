<?php

namespace LadocDocumentation\Service;


use Application\Service\AbstractBaseService;
use Application\Service\EntityDoesNotExistException;

abstract class WeightAndDimensionsService extends AbstractBaseService {
    protected abstract function getEntityRepository();
    public abstract function savePostedData($weightAndDimensions, $post);
    protected abstract function createNewWeightAndDimensionsInternal($documentation);

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

    public function createNewWeightAndDimensions($documentationId) {
        $documentation = $this->getRepository('LadocDocumentation\Entity\LadocDocumentation')->find($documentationId);
        if ($documentation) {
            return $this->createNewWeightAndDimensionsInternal($documentation);
        } else {
            throw new EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }
    }
}