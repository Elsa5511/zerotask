<?php

namespace LadocDocumentation\Service;

use Application\Service\AbstractBaseService;
use Application\Service\EntityDoesNotExistException;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Entity\LadocDocumentationDescription;
use Equipment\Entity\EquipmentTaxonomyTemplateTypes;

class LadocDocumentationService extends AbstractBaseService {

    public function getNextWizardPage($currentPage, $direction, $type) {
        if (empty($currentPage)) {
            return LadocDocumentation::PAGE_BASIC_INFORMATION;
        }

        $loadPages = array(
            1 => LadocDocumentation::PAGE_BASIC_INFORMATION,
            2 => LadocDocumentation::PAGE_WEIGHT_AND_DIMENSIONS,
            3 => LadocDocumentation::PAGE_LASHING_POINTS,
            4 => LadocDocumentation::PAGE_LIFTING_POINTS,
            5 => LadocDocumentation::PAGE_DOCUMENTATION_ATTACHMENTS,
        );

        $carrierPages = array(
            1 => LadocDocumentation::PAGE_BASIC_INFORMATION,
            2 => LadocDocumentation::PAGE_WEIGHT_AND_DIMENSIONS,
            3 => LadocDocumentation::PAGE_LASHING_POINTS,
            4 => LadocDocumentation::PAGE_LASHING_EQUIPMENT,
            5 => LadocDocumentation::PAGE_DOCUMENTATION_ATTACHMENTS,
        );

        $pages = ($type === LadocDocumentation::TYPE_CARRIER ? $carrierPages : $loadPages);
        $step = $direction === LadocDocumentation::DIRECTION_NEXT ? 1 : -1;
        $currentPageIndex = array_search($currentPage, $pages);
        $nextPageIndex = $currentPageIndex + $step;

        if ($nextPageIndex <= 0) {
            $nextPageIndex = 1;
        }
        else if ($nextPageIndex > count($pages)) {
            return LadocDocumentation::PAGE_END;
        }

        return $pages[$nextPageIndex];
    }

    public function createDocumentation($equipmentId, $type) {
        $repolux = $this->getEquipmentRepository();
        $equipment = $repolux->find($equipmentId);

        if ($equipment) {
            $documentation = new LadocDocumentation();
            $documentation->setEquipment($equipment);
            $documentation->setType($type);
            $documentation->setFinished(false);
            return $this->persist($documentation)->getId();
        }
        else {
            throw new EntityDoesNotExistException("Equipment does not exist.");
        }
    }

    public function findById($entityId) {
        $documentation = parent::findById($entityId);
        if ($documentation) {
            $this->setDocumentationEntries($documentation);
        }
        return $documentation;
    }


    public function findByEquipment($equipmentId) {
        $documentationArray = parent::findByEquipment($equipmentId);
        if (count($documentationArray) > 0) {
            return $documentationArray[0];
        }
        else {
            return null;
        }
    }

    /**
     * @param \LadocDocumentation\Entity\LadocDocumentation $documentation
     */
    private function setDocumentationEntries($documentation) {
        $basicInformationRepository = null;
        if ($documentation->getType() === 'load') {
            $basicInformationRepository = $this->getRepository('LadocDocumentation\Entity\LoadBasicInformation');
        }
        else if ($documentation->getType() === 'carrier') {
            $basicInformationRepository = $this->getRepository('LadocDocumentation\Entity\CarrierBasicInformation');
        }

        $basicInformationArray = $basicInformationRepository->findBy(array('ladocDocumentation' => $documentation->getId()));
        if (count($basicInformationArray) > 0) {
            $documentation->setBasicInformation($basicInformationArray[0]);
        }
    }

    private function getDescriptionFunction($type, $prefix) {
        switch ($type) {
            case 'load-lashing-point':
            case 'carrier-lashing-point':
                return "{$prefix}LashingPointDescription";
            case 'load-lifting-point':
                return "{$prefix}LiftingPointDescription";
            case 'ladoc-documentation-attachment':
                return "{$prefix}DocumentationAttachmentDescription";
            case 'carrier-lashing-equipment':
                return "{$prefix}LashingEquipmentDescription";
            default:
                return null;
        }
    }

    public function getDocumentationDescriptionValue($type, LadocDocumentation $ladocDocumentation) {
        if ($ladocDocumentation->hasDescriptionInformation()) {
            $ladocDocumentationDescription = $ladocDocumentation->getDescriptionInformation();

            $functionName = $this->getDescriptionFunction($type, "get");
            if ($functionName)
                return $ladocDocumentationDescription->$functionName();
        }

        return null;
    }

    public function getDocumentationDescription($type, $value, LadocDocumentation $ladocDocumentation) {
        if ($ladocDocumentation->hasDescriptionInformation())
            $ladocDocumentationDescription = $ladocDocumentation->getDescriptionInformation();
        else {
            $ladocDocumentationDescription = new LadocDocumentationDescription();
            $ladocDocumentationDescription->setLadocDocumentation($ladocDocumentation);
        }

        $functionName = $this->getDescriptionFunction($type, "set");
        if ($functionName)
            $ladocDocumentationDescription->$functionName($value);
        else
            return null;

        return $ladocDocumentationDescription;
    }

    /**
     * @param LadocDocumentation $ladocDocumentation
     * @return int The template type of the lowest taxonomy. If there is no taxonomy type, this searches in the parent taxonomy until it finds a value
     */
    public function getLowestTaxonomyTemplateType(LadocDocumentation $ladocDocumentation) {
        $templateType = null;
        $taxonomy = $ladocDocumentation->getEquipment()->getFirstEquipmentTaxonomy();
        while($taxonomy) {
            $templateType = $taxonomy->getTemplateType();
            if($templateType)   break;

            $taxonomy = $taxonomy->getParent();
        }

        return $templateType ? $templateType : EquipmentTaxonomyTemplateTypes::COUNTRY_ROAD;
    }

    protected function getEntityRepository() {
        return $this->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    private function getEquipmentRepository() {
        return $this->getRepository('Equipment\Entity\Equipment');
    }
}