<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use LadocDocumentation\Entity\CarrierLashingPoint;

class CarrierLashingPointService extends PointService {

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierLashingPoint');
    }

    public function getContentDirname () {
        return 'carrier-lashing-point';
    }

    public function getNewPoint($documentationId)
    {
        $ladocDocumentation = $this->getDocumentationRepository()->find($documentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $carrierLashingPoint = new CarrierLashingPoint();
        $carrierLashingPoint->setLadocDocumentation($ladocDocumentation);
        
        return $carrierLashingPoint;
    }

    public function saveAttachmentsFiles(CarrierLashingPoint &$postObject, $postData)
    {
        if($postObject->getLashingPointId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($postObject);
        else
            $attachmentsFromDb = array();
            
        $attachmentsFromPost = $postObject->getCarrierLashingPointAttachments();
        if($attachmentsFromPost) {
            $postAttachmentsKeys = array_keys($postData["point"]["carrierLashingPointAttachments"]);
            foreach ($attachmentsFromPost as $k => $attachment) {
                $imageData = $postData["point"]["carrierLashingPointAttachments"][$postAttachmentsKeys[$k]]["filename"];
                if(!empty($imageData['tmp_name']))
                    $attachment->setFile($this->saveFile($imageData, 1500, $attachment->getFile()));

                $attachment->setCarrierLashingPoint($postObject);
                $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
            }
        }
            
        if($postObject->getLashingPointId() > 0)
            $this->deleteAttachments($attachmentsFromDb);
    }

    private function getAttachmentsFromDb(CarrierLashingPoint $lashingPoint){
        $attachments = $this->getAttachmentRepository()->findBy(array("carrierLashingPoint" => $lashingPoint->getLashingPointId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    public function mergeWithAttachments ($post, $files, $attachmentsIndex = null)
    {
        return parent::mergeWithAttachments($post, $files, "carrierLashingPointAttachments");
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getAttachmentRepository()
    {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierLashingPointAttachment');
    }
}