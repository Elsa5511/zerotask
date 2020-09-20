<?php

namespace LadocDocumentation\Service;


use Application\Utility\Image;
use LadocDocumentation\Entity\LoadWeightAndDimensions;

class LoadWeightAndDimensionsService extends WeightAndDimensionsService {
    const IMAGE_REMOVED = 1;

    protected function createNewWeightAndDimensionsInternal($documentation) {
        $loadWeightAndDimensions = new LoadWeightAndDimensions();
        $loadWeightAndDimensions->setLadocDocumentation($documentation);
        return $loadWeightAndDimensions;
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadWeightAndDimensions');
    }

    public function mergeWithAttachments($post, $files, $attachmentsIndex = "attachments")
    {
        if(array_key_exists($attachmentsIndex, $post["weight-and-dimensions"]) && is_array($post["weight-and-dimensions"][$attachmentsIndex])) {
            foreach($post["weight-and-dimensions"][$attachmentsIndex] as $k => $v) {
                if(isset($files["weight-and-dimensions"]) && array_key_exists($k, $files["weight-and-dimensions"][$attachmentsIndex]))
                    foreach($files["weight-and-dimensions"][$attachmentsIndex][$k] as $k2 => $v2)
                        $post["weight-and-dimensions"][$attachmentsIndex][$k][$k2] = $v2;
            }
        }
        return $post;
    }

    public function validateForCustom($postData, &$error)
    {
        $attachmentsIndex = 'attachments';
        $customError = array( 'attachments' => array(), 'new_attachments' => array() );
        if(isset($postData['weight-and-dimensions'][$attachmentsIndex]) && is_array($postData['weight-and-dimensions'][$attachmentsIndex])) {
            foreach ($postData['weight-and-dimensions'][$attachmentsIndex] as $key => $value) {
                if( (empty($value['filename']['tmp_name']) && empty($value['pointAttachmentId']))
                    || (empty($value['filename']['tmp_name']) && !empty($value['pointAttachmentId']) && $value['removed_image'] == self::IMAGE_REMOVED) ) {
                    $customError['attachments'][$key] = $this->translate('The file was not detected');
                } elseif( (!empty($value['filename']['tmp_name']) && empty($value['pointAttachmentId'])) ) {
                    $customError['new_attachments'][$key] = '';
                }
            }
        }

        if(count($customError['attachments']) > 0) {
            $error = $customError;
            return false;
        }
        else
            return true;
    }

    private function getAttachmentsFromDb(LoadWeightAndDimensions $entity){
        $attachments = $this->getAttachmentRepository()->findBy(array("loadWeightAndDimensions" => $entity->getId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    protected function getAttachmentRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LoadWeightAndDimensionsAttachment');
    }

    private function saveAttachmentsFiles(LoadWeightAndDimensions $entity, $postData)
    {
        if($entity->getId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($entity);
        else
            $attachmentsFromDb = array();

        $attachmentsFromEntity = $entity->getAttachments();
        if($attachmentsFromEntity) {
            if(isset($postData["weight-and-dimensions"]["attachments"])) {
                $postAttachmentsKeys = array_keys($postData["weight-and-dimensions"]["attachments"]);
                foreach ($attachmentsFromEntity as $k => $attachment) {
                    $imageData = $postData["weight-and-dimensions"]["attachments"][$postAttachmentsKeys[$k]]["filename"];
                    if (!empty($imageData['tmp_name']))
                        $attachment->setFile($this->saveFile($imageData, 1500, $attachment->getFile()));

                    $attachment->setLoadWeightAndDimensions($entity);
                    $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
                }
            }
        }

        if($entity->getId() > 0) {
            $this->deleteAttachments($attachmentsFromDb);
        }

    }

    protected function resizeImage($post, $limit, $fileImage) {
        if (!empty($post['tmp_name'])) {
            $image = new Image();
            if($fileImage != null)
                $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $fileImage);

            return $image->resizeImage(
                $post['tmp_name'], $limit, 'public/content/' . $this->getContentDirname() . '/' .
                $post['name']);
        }
        return null;
    }

    protected function saveFile($file, $limit, $existentFile) {
        if (!empty($file['tmp_name'])) {
            $fileInfo = pathinfo($file['name']);
            if (Image::hasImageFormat($file['name'])) {
                return $this->resizeImage($file, $limit, $existentFile);
            } else {
                $dirPath = './public/content/' . $this->getContentDirname() . '/';
                if($existentFile != null)
                    unlink($dirPath . $existentFile);

                $fileName = sha1($fileInfo['filename'] . time()) . '.' . $fileInfo['extension'];
                copy($file['tmp_name'], $dirPath . $fileName);
                return $fileName;
            }
        }
        return null;
    }

    public function getContentDirname () {
        return 'weight-and-dimensions';
    }

    public function deleteAttachments($attachments)
    {
        foreach($attachments as $attachment) {
            $fileName = $attachment->getFile();
            if (Image::hasImageFormat($fileName)) {
                $image = new Image();
                $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $fileName);
            } else {
                $filePath = './public/content/' . $this->getContentDirname() . '/' . $fileName;
                unlink($filePath);
            }
            parent::remove ($attachment);
        }
    }

    protected function removeAttachmentFromArray(&$attachments, $attachment) {
        foreach ($attachments as $i => $att) {
            if ($att->getPointAttachmentId() == $attachment->getPointAttachmentId()) {
                array_splice($attachments, $i, 1);
                break;
            }
        }
    }

    public function savePostedData($weightAndDimensions, $post) {
        $this->saveAttachmentsFiles($weightAndDimensions, $post);
        $this->persist($weightAndDimensions);
    }
}