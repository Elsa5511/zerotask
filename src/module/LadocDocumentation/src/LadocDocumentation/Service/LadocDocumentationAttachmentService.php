<?php

namespace LadocDocumentation\Service;

use Application\Service\AbstractBaseService;
use LadocDocumentation\Entity\LadocDocumentationAttachment;
use Application\Utility\Image;

class LadocDocumentationAttachmentService extends AbstractBaseService {

	public function getContentDirname () {
        return 'ladoc-documentation-attachment';
    }

    public function findByLadocDocumentation($documentationId) {
        return $this->getEntityRepository()->findBy(array('ladocDocumentation' => $documentationId));
    }

	public function getNewDocumentationAttachment($documentationId)
    {
        $ladocDocumentation = $this->getDocumentationRepository()->find($documentationId);

        if (!$ladocDocumentation) {
            throw new \Application\Service\EntityDoesNotExistException($this->getStandardMessages()->ladocDocumentationDoesNotExist());
        }

        $documentationAttachment = new LadocDocumentationAttachment();
        $documentationAttachment->setLadocDocumentation($ladocDocumentation);
        
        return $documentationAttachment;
    }

    public function deleteFile($entity)
    {
        $fileName = $entity->getFile();
        if (Image::hasImageFormat($fileName)) {
            $image = new Image();
            $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $fileName);
        } else {
            $filePath = './public/content/' . $this->getContentDirname() . '/' . $fileName;
            unlink($filePath);
        }
    }

    public function saveAttachment($file, $limit, $existentFile) {
        if (!empty($file['tmp_name'])) {
            $fileInfo = pathinfo($file['name']);
            if (Image::hasImageFormat($file['name'])) {
                $image = new Image();
                if ($existentFile != null)
                    $image->deleteImage('public/content/' . $this->getContentDirname() . '/' . $existentFile);

                return $image->resizeImage(
                    $file['tmp_name'], $limit, 'public/content/' . $this->getContentDirname() . '/' .
                    $file['name']);
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

    public function persistData($documentationAttachment)
    {
        parent::persist($documentationAttachment);
        return $documentationAttachment->getPointAttachmentId();
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentationAttachment');
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }
}