<?php

namespace LadocDocumentation\Controller\Helper;

use LadocDocumentation\Entity\CarrierWeightAndDimensions;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Entity\LoadWeightAndDimensions;

class DocumentEntryViewMapper {

    private $documentation;
    private $type;

    /**
     * @param \LadocDocumentation\Entity\LadocDocumentation $documentation
     */
    public function __construct(LadocDocumentation $documentation) {
        $this->documentation = $documentation;
        $this->type = $documentation->getType();
    }

    public function getBasicInformationValues($nsnNumber, $sapNumber) {
        $basicInformation = $this->documentation->getBasicInformation();

        if ($basicInformation) {
            $values = array(
                'id' => $basicInformation->getId(),
                'nsn' => $nsnNumber,
                'sap' => $sapNumber,
                'type' => $this->type,
                'image' => $basicInformation->getImage(),
                'approvedName' => $basicInformation->getApprovedName(),
                'colloquialName' => $basicInformation->getColloquialName(),
                'approvedFormsOfTransportation' => $basicInformation->getApprovedFormsOfTransportation(),
                'stanags' => $basicInformation->getStanags(),
                'mlc' => $basicInformation->getMlc(),
                'responsibleOffices' => $basicInformation->getResponsibleOffices()
            );

            if ($this->type == LadocDocumentation::TYPE_CARRIER) {
                $values['technicalPayload'] = $basicInformation->getTechnicalPayload();
            }
            else if ($this->type == LadocDocumentation::TYPE_LOAD) {
                $values['equivalentModels'] = $basicInformation->getEquivalentModels();
            }

            return $values;
        }
        else {
            return array();
        }

    }

    public function getLashingPointValues() {
        return array(
            'type' => $this->type, 
            'documentationId' => $this->documentation->getId(),
            'descriptionInformation' => $this->documentation->getDescriptionInformation(),
            'points' => $this->documentation->getLashingPoints(),
            'attachments' => $this->getUniqueAttachments($this->documentation->getLashingPoints()->toArray())
        );
    }

    public function getLiftingPointValues() {
        return array(
            'type' => $this->type, 
            'documentationId' => $this->documentation->getId(),
            'descriptionInformation' => $this->documentation->getDescriptionInformation(),
            'points' => $this->documentation->getLiftingPoints(),
            'attachments' => $this->getUniqueAttachments($this->documentation->getLiftingPoints()->toArray())
        );
    }

    public function getWeightAndDimensionsValues() {
        $weightAndDimensions = $this->documentation->getWeightAndDimensions();

        if ($this->type === LadocDocumentation::TYPE_LOAD) {
            return $this->getLoadWeightAndDimensionValues($weightAndDimensions);
        }
        else if ($this->type === LadocDocumentation::TYPE_CARRIER) {
            return $this->getCarrierWeightAndDimensionValues($weightAndDimensions);
        }
    }

    private function getLoadWeightAndDimensionValues(LoadWeightAndDimensions $weightAndDimensions) {
        return array(
            'type' => $this->type,
            'id' => $weightAndDimensions->getId(),
            'length' => $weightAndDimensions->getLength(),
            'width' => $weightAndDimensions->getWidth(),
            'maxHeightWithOwnWeight' => $weightAndDimensions->getMaxHeightWithOwnWeight(),
            'groundClearanceWithOwnWeight' => $weightAndDimensions->getGroundClearanceWithOwnWeight(),
            'ownWeight' => $weightAndDimensions->getOwnWeight(),
            'technicalTotalWeight' => $weightAndDimensions->getTechnicalTotalWeight(),
            'gravityWithOwnWeight' => $weightAndDimensions->getGravityWithOwnWeight(),
            'gravityWithTotalWeigth' => $weightAndDimensions->getGravityWithTotalWeigth(),
            'gaugeOfWheels' => $weightAndDimensions->getGaugeOfWheels(),
            'overhangAngle' => $weightAndDimensions->getOverhangAngle(),
            'overhang' => $weightAndDimensions->getOverhang(),
            'additionalInfo' => $weightAndDimensions->getAdditionalInfo(),
            'attachments' => $this->getUniqueAttachments(array($weightAndDimensions))
        );
    }

    private function getCarrierWeightAndDimensionValues(CarrierWeightAndDimensions $weightAndDimensions) {
        $attachmentParents = array(
            $weightAndDimensions->getOwnWeight(),
            $weightAndDimensions->getTechnicalWeight(),
            $weightAndDimensions->getOwnDimensions(),
            $weightAndDimensions->getLoadingPlanDimensions()
        );

        return array(
            'type' => $this->type,
            'id' => $weightAndDimensions->getId(),
            'ownWeight' => $weightAndDimensions->getOwnWeight(),
            'technicalWeight' => $weightAndDimensions->getTechnicalWeight(),
            'weightAdditionalInfo' => $weightAndDimensions->getWeightAdditionalInfo(),
            'ownDimensions' => $weightAndDimensions->getOwnDimensions(),
            'loadingPlanDimensions' => $weightAndDimensions->getLoadingPlanDimensions(),
            'dimensionsAdditionalInfo' => $weightAndDimensions->getDimensionsAdditionalInfo(),
            'attachments' => $this->getUniqueAttachments($attachmentParents)
        );
    }

    private function getUniqueAttachments(array $attachmentParents) {
        $titleArray = array();
        $attachmentsArray = array();
        foreach($attachmentParents as $parent) {
            $attachments = $parent->getAttachments();
            foreach ($attachments as $attachment) {
                if (!in_array($attachment->getTitle(), $titleArray)) {
                    $attachmentsArray[] = $attachment;
                    $titleArray[] = $attachment->getTitle();
                }
            }
        }
        return $attachmentsArray;
    }

    public function getDocumentationAttachmentValues ()
    {
        return array(
            'documentationId' => $this->documentation->getId(),
            'descriptionInformation' => $this->documentation->getDescriptionInformation(),
            'attachments' => $this->documentation->getDocumentationAttachments()
        );
    }

    public function getLashingEquipmentValues() {
        return array(
            'type' => $this->type, 
            'documentationId' => $this->documentation->getId(),
            'descriptionInformation' => $this->documentation->getDescriptionInformation(),
            'points' => $this->documentation->getLashingEquipments(),
            'attachments' => $this->getUniqueAttachments($this->documentation->getLashingEquipments()->toArray())
        );
    }

    public function getRestraintCertifiedValues () {
        return array(
            'type' => $this->type, 
            'documentationId' => $this->documentation->getId(),
            'entities' => $this->type == 'load' ? $this->documentation->getLoadRestraintCertifieds() : $this->documentation->getCarrierRestraintCertifieds()
        );
    }

    public function getRestraintNonCertifiedValues () {
        return array(
            'type' => $this->type, 
            'documentationId' => $this->documentation->getId(),
            'entities' => $this->type == 'load' ? $this->documentation->getLoadRestraintNonCertifieds() : $this->documentation->getCarrierRestraintNonCertifieds()
        );
    }

}