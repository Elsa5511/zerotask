<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use LadocDocumentation\Entity\CarrierLashingEquipment;

class CarrierLashingEquipmentService extends PointService {

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierLashingEquipment');
    }

    public function getContentDirname () {
        return 'carrier-lashing-equipment';
    }

    public function getNewPoint($ladocDocumentationId)
    {
        $ladocDocumentation = $this->getDocumentationRepository()->find($ladocDocumentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $carrierLashingEquipment = new CarrierLashingEquipment();
        $carrierLashingEquipment->setLadocDocumentation($ladocDocumentation);
        
        return $carrierLashingEquipment;
    }

    public function saveAttachmentsFiles(CarrierLashingEquipment &$postObject, $postData)
    {
        if($postObject->getLashingEquipmentId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($postObject);
        else
            $attachmentsFromDb = array();
            
        $attachmentsFromPost = $postObject->getCarrierLashingEquipmentAttachments();
        if($attachmentsFromPost) {
            $postAttachmentsKeys = array_keys($postData["point"]["carrierLashingEquipmentAttachments"]);
            foreach ($attachmentsFromPost as $k => $attachment) {
                $imageData = $postData["point"]["carrierLashingEquipmentAttachments"][$postAttachmentsKeys[$k]]["filename"];
                if(!empty($imageData['tmp_name']))
                    $attachment->setFile($this->saveFile($imageData, 1500, $attachment->getFile()));

                $attachment->setCarrierLashingEquipment($postObject);
                $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
            }
        }
            
        if($postObject->getLashingEquipmentId() > 0)
            $this->deleteAttachments($attachmentsFromDb);
    }

    private function getAttachmentsFromDb(CarrierLashingEquipment $lashingEquipment){
        $attachments = $this->getAttachmentRepository()->findBy(array("carrierLashingEquipment" => $lashingEquipment->getLashingEquipmentId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    public function mergeWithAttachments ($post, $files, $attachmentsIndex = null)
    {
        return parent::mergeWithAttachments($post, $files, "carrierLashingEquipmentAttachments");
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getAttachmentRepository()
    {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\carrierLashingEquipmentAttachment');
    }
}