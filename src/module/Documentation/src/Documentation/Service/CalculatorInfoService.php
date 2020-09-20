<?php

namespace Documentation\Service;


use Documentation\Entity\CalculatorInfo;
use Documentation\Entity\CalculatorAttachment;
use Application\Utility\Image;

class CalculatorInfoService extends \Acl\Service\AbstractService
{
    public function getContentDirname () {
        return 'load-security';
    }

    public function findAttachmentById($attachmentId){
        return $this->getEntityAttachmentRepository()->find($attachmentId);
    }

    public function getData()
    {
        $entityRepository = $this->getEntityRepository();
        $result = $entityRepository->findAll();

        if($result && count($result) > 0)
            return $result[0];

        return new CalculatorInfo();
    }

    public function getNewAttachment()
    {
        $calculatorAttachment = new CalculatorAttachment();
        $calculatorAttachment->setCalculatorInfo($this->getData());
        return $calculatorAttachment;
    }

    public function persistData(\Documentation\Entity\CalculatorInfo $calculatorInfo)
    {
        parent::persist($calculatorInfo);
    }

    public function persistAttachment(\Documentation\Entity\CalculatorAttachment $calculatorAttachment)
    {
        parent::persist($calculatorAttachment);
        return $calculatorAttachment->getId();
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

    /**
     * @return \Acl\Repository\EntityRepository
     */
    private function getEntityRepository()
    {
        return $this->getRepository('Documentation\Entity\CalculatorInfo');
    }

    /**
     * @return \Acl\Repository\EntityRepository
     */
    private function getEntityAttachmentRepository()
    {
        return $this->getRepository('Documentation\Entity\CalculatorAttachment');
    }
}