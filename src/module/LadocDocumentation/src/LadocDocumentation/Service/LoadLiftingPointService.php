<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use LadocDocumentation\Entity\LoadLiftingPoint;

class LoadLiftingPointService extends PointService {

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadLiftingPoint');
    }

    public function getContentDirname () {
        return 'load-lifting-point';
    }

    public function getNewPoint($ladocDocumentationId)
    {
        $ladocDocumentation = $this->getDocumentationRepository()->find($ladocDocumentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->translate('Load Documentation does not exist.'));
        }

        $loadLiftingPoint = new LoadLiftingPoint();
        $loadLiftingPoint->setLadocDocumentation($ladocDocumentation);
        
        return $loadLiftingPoint;
    }

    public function saveAttachmentsFiles(LoadLiftingPoint &$postObject, $postData)
    {
        if($postObject->getLiftingPointId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($postObject);
        else
            $attachmentsFromDb = array();
            
        $attachmentsFromPost = $postObject->getLoadLiftingPointAttachments();
        if($attachmentsFromPost) {
            $postAttachmentsKeys = array_keys($postData["point"]["loadLiftingPointAttachments"]);
            foreach ($attachmentsFromPost as $k => $attachment) {
                $imageData = $postData["point"]["loadLiftingPointAttachments"][$postAttachmentsKeys[$k]]["filename"];
                if(!empty($imageData['tmp_name']))
                    $attachment->setFile($this->saveFile($imageData, 1500, $attachment->getFile()));

                $attachment->setLoadLiftingPoint($postObject);
                $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
            }
        }
            
        if($postObject->getLiftingPointId() > 0)
            $this->deleteAttachments($attachmentsFromDb);
    }

    private function getAttachmentsFromDb(LoadLiftingPoint $liftingPoint){
        $attachments = $this->getAttachmentRepository()->findBy(array("loadLiftingPoint" => $liftingPoint->getLiftingPointId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    public function mergeWithAttachments ($post, $files, $attachmentsIndex = null)
    {
        return parent::mergeWithAttachments($post, $files, "loadLiftingPointAttachments");
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getAttachmentRepository()
    {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadLiftingPointAttachment');
    }
}