<?php

namespace LadocDocumentation\Service;

use Application\Service\AbstractBaseService;
use LadocDocumentation\Entity\LadocRestraintCertifiedDocument;
use Application\Utility\Image;

class RestraintCertifiedDocumentService extends AbstractBaseService {

	public function getContentDirname () {
        return 'restraint-certified-document';
    }

    public function findByRestraintCertified($restraintCertifiedId) {
        return $this->getEntityRepository()->findBy(array('ladocRestraintCertified' => $restraintCertifiedId));
    }

	public function getNewRestraintCertifiedDocument($restraintCertifiedId)
    {
        $restraintCertified = $this->getRestraintCertifiedRepository()->find($restraintCertifiedId);

        if (!$restraintCertified) {
            throw new \Application\Service\EntityDoesNotExistException();
        }

        $restraintCertifiedDocument = new LadocRestraintCertifiedDocument();
        $restraintCertifiedDocument->setLadocRestraintCertified($restraintCertified);
        
        return $restraintCertifiedDocument;
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

    public function saveDocument($file, $limit, $existentFile) {
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

    public function persistData($restraintDocument)
    {
        parent::persist($restraintDocument);
        return $restraintDocument->getPointAttachmentId();
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocRestraintCertifiedDocument');
    }

    protected function getRestraintCertifiedRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocRestraintCertified');
    }
}