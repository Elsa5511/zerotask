<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use LadocDocumentation\Entity\LoadLashingPoint;

class LoadLashingPointService extends PointService {

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadLashingPoint');
    }

    public function getContentDirname () {
        return 'load-lashing-point';
    }

    public function getNewPoint($documentationId)
    {
        $ladocDocumentation = $this->getDocumentationRepository()->find($documentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $loadLashingPoint = new LoadLashingPoint();
        $loadLashingPoint->setLadocDocumentation($ladocDocumentation);
        
        return $loadLashingPoint;
    }

    public function saveAttachmentsFiles(LoadLashingPoint &$postObject, $postData)
    {
        if($postObject->getLashingPointId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($postObject);
        else
            $attachmentsFromDb = array();
            
        $attachmentsFromPost = $postObject->getLoadLashingPointAttachments();
        if($attachmentsFromPost) {
            $postAttachmentsKeys = array_keys($postData["point"]["loadLashingPointAttachments"]);
            foreach ($attachmentsFromPost as $k => $attachment) {
                $imageData = $postData["point"]["loadLashingPointAttachments"][$postAttachmentsKeys[$k]]["filename"];
                if(!empty($imageData['tmp_name']))
                    $attachment->setFile($this->saveFile($imageData, 1500, $attachment->getFile()));

                $attachment->setLoadLashingPoint($postObject);
                $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
            }
        }
            
        if($postObject->getLashingPointId() > 0)
            $this->deleteAttachments($attachmentsFromDb);
    }

    private function getAttachmentsFromDb(LoadLashingPoint $lashingPoint){
        $attachments = $this->getAttachmentRepository()->findBy(array("loadLashingPoint" => $lashingPoint->getLashingPointId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    public function mergeWithAttachments ($post, $files, $attachmentsIndex = null)
    {
        return parent::mergeWithAttachments($post, $files, "loadLashingPointAttachments");
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getAttachmentRepository()
    {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadLashingPointAttachment');
    }
}