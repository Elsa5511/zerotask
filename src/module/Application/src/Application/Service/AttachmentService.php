<?php

namespace Application\Service;

use Acl\Service\AbstractService;
use Application\Utility\Image;

class AttachmentService extends AbstractService
{

    const PATH_ATTACHMENT = './public/attachment/';
    const PATH_ATTACHMENT_ORIGINAL = './public/attachment/original/';

    private function getEntityRepository()
    {
        return $this->getRepository($this->attachmentRepositoryString);
    }

    public function persistAttachment($attachment)
    {
        parent::persist($attachment);
    }

    public function getAttachmentPath()
    {
        return self::PATH_ATTACHMENT;
    }

    public function getHowIsOpened($mimeType)
    {
        $howisOpened = "attachment";
        $isPDFFile = stripos($mimeType, 'application/pdf') === 0;
        $isImageFile = stripos($mimeType, 'image/') === 0;
        if ($isPDFFile || $isImageFile) {
            $howisOpened = 'inline';
        }
        return $howisOpened;
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = $this->getAttachment($attachmentId);
        if (empty($attachment)) {
            return false;
        }
        $this->removeAttachmentFile($attachment->getFile());

        $this->remove($attachment);
        return true;
    }

    public function deleteByIds($attachmentIds)
    {
        $countDeleted = 0;
        $countFailed = 0;
        foreach ($attachmentIds as $attachmentId) {
            if ($this->deleteAttachment($attachmentId)) {
                $countDeleted++;
            } else {
                $countFailed++;
            }
        }
        return array('deleted' => $countDeleted, 'fails' => $countFailed);
    }

    public function removeAttachmentFile($filename)
    {

        if (!empty($filename)) {

            $source = self::PATH_ATTACHMENT . $filename;
            $originalSource = self::PATH_ATTACHMENT . 'original/' . $filename;
            if (file_exists($source)) {

                unlink($source);
            }
            if (file_exists($originalSource)) {

                unlink($originalSource);
            }
        }
    }

    public function copyAttachmentFile($file)
    {
        $this->image['width'];
        $fileInfo = pathinfo($file['name']);
        $extension = $fileInfo['extension'];
        $isImage = in_array(strtolower($extension), array('jpg', 'gif', 'jpeg', 'png'));
        if ($isImage) {

            $fileName = $this->resizeImage($file, $fileInfo['filename']);
            copy($file['tmp_name'], self::PATH_ATTACHMENT_ORIGINAL . $fileName);
        } else {
            $fileName = sha1($fileInfo['filename'] . time()) . '.' . $extension;
            copy($file['tmp_name'], self::PATH_ATTACHMENT . $fileName);
        }

        return $fileName;
    }
    
    public function createDuplicateAttachmentFile($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $filenameForDuplicate = sha1($filename . time()) . '.' . $extension;
        copy(self::PATH_ATTACHMENT .$filename, self::PATH_ATTACHMENT . $filenameForDuplicate);
        return $filenameForDuplicate;        
    }

    protected function resizeImage($imagePost, $currentImage)
    {
        $folderPath = self::PATH_ATTACHMENT;
        $image = new Image();
        if ($currentImage) {
            $image->deleteImage($folderPath . $currentImage);
        }
        $newImage = $image->resizeImage(
                $imagePost['tmp_name'], $this->image['width'], $folderPath . $imagePost['name']);
        return $newImage;
    }

    public function getAttachment($attachmentId)
    {

        return $this->getEntityRepository()->find($attachmentId);
    }

    public function fetchAttachment($criteria = array())
    {

        return $this->getEntityRepository()->findBy($criteria);
    }

}

