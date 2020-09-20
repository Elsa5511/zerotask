<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use Application\Service\AbstractBaseService;

abstract class PointService extends AbstractBaseService {
    const IMAGE_REMOVED = 1;

    protected abstract function getEntityRepository();

    protected abstract function getContentDirname();

    protected abstract function getDocumentationRepository();

    protected abstract function getNewPoint($documentationId);

    public function findByLadocDocumentation($documentationId) {
        return $this->getEntityRepository()->findBy(array('ladocDocumentation' => $documentationId));
    }

    private function resizeImage($file, $limit, $fileImage) {
        if (!empty($file['tmp_name'])) {
            $image = new Image();
            if($fileImage != null)
                $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $fileImage);
            
            return $image->resizeImage(
                            $file['tmp_name'], $limit, 'public/content/' . $this->getContentDirname() . '/' .
                            $file['name']);
        }
        return null;
    }

    protected function saveFile($file, $limit, $existentFile)
    {
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
            }

            return $fileName;
        }
        return null;
    }

    public function persistData($point)
    {
        parent::persist($point);
        return $point->getId();
    }

    public function deleteAttachments($attachments)
    {
        foreach($attachments as $attachment) {
            if (Image::hasImageFormat($attachment->getFile())) {
                $image = new Image();
                $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $attachment->getFile());
            } else {
                $filePath = './public/content/' . $this->getContentDirname() . '/' . $attachment->getFile();
                unlink($filePath);
            }

            $this->remove($attachment);
        }
    }

    protected function removeAttachmentFromArray(&$attachments, $attachment){
        foreach($attachments as $i => $att){
            if($att->getPointAttachmentId() == $attachment->getPointAttachmentId()){
                array_splice($attachments, $i, 1);
                break;
            }
        }
    }

    protected function mergeWithAttachments ($post, $files, $attachmentsIndex)
    {
        if(array_key_exists($attachmentsIndex, $post["point"]) && is_array($post["point"][$attachmentsIndex])) {
            foreach($post["point"][$attachmentsIndex] as $k => $v) {
                if(isset($files["point"]) && array_key_exists($k, $files["point"][$attachmentsIndex]))
                    foreach($files["point"][$attachmentsIndex][$k] as $k2 => $v2)
                        $post["point"][$attachmentsIndex][$k][$k2] = $v2;
            }
        }/* else {
            $post["point"][$attachmentsIndex] = array();
        }*/
        return $post;
    }

    public function validateFormCustom($postData, $attachmentsIndex, &$error)
    {
        $customError = array( 'attachments' => array(), 'new_attachments' => array() );
        if(isset($postData['point'][$attachmentsIndex]) && is_array($postData['point'][$attachmentsIndex])) {
            foreach ($postData['point'][$attachmentsIndex] as $key => $value) {
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
}