<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use LadocDocumentation\Entity\CarrierDimensions;
use LadocDocumentation\Entity\CarrierWeight;
use LadocDocumentation\Entity\CarrierWeightAndDimensions;

class CarrierWeightAndDimensionsService extends WeightAndDimensionsService {
    protected function createNewWeightAndDimensionsInternal($documentation) {
        $carrierWeightAndDimensions = new CarrierWeightAndDimensions();
        $carrierWeightAndDimensions->setLadocDocumentation($documentation);
        $carrierWeightAndDimensions->setOwnWeight(new CarrierWeight());
        $carrierWeightAndDimensions->setTechnicalWeight(new CarrierWeight());
        $carrierWeightAndDimensions->setOwnDimensions(new CarrierDimensions());
        $carrierWeightAndDimensions->setLoadingPlanDimensions(new CarrierDimensions());
        return $carrierWeightAndDimensions;
    }

    const FORM_INDEX = "weight-and-dimensions";
    const ATTACHMENT_INDEX = 'attachments';
    const OWN_WEIGHT_INDEX ="ownWeight";
    const TECHNICAL_WEIGHT_INDEX = "technicalWeight";
    const OWN_DIMENSIONS_INDEX = "ownDimensions";
    const LOADING_PLAN_DIMENSIONS_INDEX = "loadingPlanDimensions";

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierWeightAndDimensions');
    }

    public function mergeWithAttachments($post, $files) {
        $sectionIndexes = array(
            self::OWN_WEIGHT_INDEX, self::TECHNICAL_WEIGHT_INDEX,
            self::OWN_DIMENSIONS_INDEX, self::LOADING_PLAN_DIMENSIONS_INDEX
        );

        foreach ($sectionIndexes as $sectionIndex) {
            $sectionPost = &$post[self::FORM_INDEX][$sectionIndex];

            if (array_key_exists(self::ATTACHMENT_INDEX, $sectionPost) &&
                is_array($sectionPost[self::ATTACHMENT_INDEX])) {
                if (array_key_exists(self::FORM_INDEX, $files) && array_key_exists($sectionIndex, $files[self::FORM_INDEX])) {
                    $this->mergeSectionWithAttachments($sectionPost[self::ATTACHMENT_INDEX],
                        $files[self::FORM_INDEX][$sectionIndex][self::ATTACHMENT_INDEX]);
                }
            }
        }
        return $post;
    }

    public function mergeSectionWithAttachments(&$sectionAttachmentPost, $sectionFiles) {

        for ($i = 0; $i < count($sectionAttachmentPost); $i++) {
            $filesExist = isset($sectionFiles);
            $existsFileForPostedFiledata = array_key_exists($i, $sectionFiles);
            if ($filesExist && $existsFileForPostedFiledata) {
                foreach ($sectionFiles[$i] as $filesKey => $filesValue) {
                    $sectionAttachmentPost[$i][$filesKey] = $filesValue;
                }
            }
        }
    }

    public function saveAttachmentFiles(CarrierWeightAndDimensions $entity, $post) {
        $postInternal = $post[self::FORM_INDEX];

        $attachmentsFromDb = array();
        if ($entity->getId() > 0) {
            $attachmentsFromDb = array_merge(
                $this->getCarrierWeightAttachmentsFromDb($entity->getOwnWeight()),
                $this->getCarrierWeightAttachmentsFromDb($entity->getTechnicalWeight()),
                $this->getCarrierDimensionsAttachmentsFromDb($entity->getOwnDimensions()),
                $this->getCarrierDimensionsAttachmentsFromDb($entity->getLoadingPlanDimensions())
            );
        }

        if (array_key_exists(self::ATTACHMENT_INDEX, $postInternal[self::OWN_WEIGHT_INDEX])) {
            $this->saveAttachmentsFilesInternal($entity->getOwnWeight(),
                $postInternal[self::OWN_WEIGHT_INDEX], $attachmentsFromDb);
        }
        if (array_key_exists(self::ATTACHMENT_INDEX, $postInternal[self::TECHNICAL_WEIGHT_INDEX])) {
            $this->saveAttachmentsFilesInternal($entity->getTechnicalWeight(),
                $postInternal[self::TECHNICAL_WEIGHT_INDEX], $attachmentsFromDb);
        }
        if (array_key_exists(self::ATTACHMENT_INDEX, $postInternal[self::OWN_DIMENSIONS_INDEX])) {
            $this->saveAttachmentsFilesInternal($entity->getOwnDimensions(),
                $postInternal[self::OWN_DIMENSIONS_INDEX], $attachmentsFromDb);
        }
        if (array_key_exists(self::ATTACHMENT_INDEX, $postInternal[self::LOADING_PLAN_DIMENSIONS_INDEX])) {
            $this->saveAttachmentsFilesInternal($entity->getLoadingPlanDimensions(),
                $postInternal[self::LOADING_PLAN_DIMENSIONS_INDEX], $attachmentsFromDb);
        }

        if ($entity->getId() > 0) {
            $this->deleteAttachments($attachmentsFromDb);
        }
    }

    private function getCarrierWeightAttachmentsFromDb(CarrierWeight $entity) {
        $attachments = $this->getCarrierWeightAttachmentRepository()->findBy(array("carrierWeight" => $entity->getId()));
        return $attachments;
    }

    private function getCarrierDimensionsAttachmentsFromDb(CarrierDimensions $entity) {
        $attachments = $this->getCarrierDimensionsAttachmentRepository()->findBy(array("carrierDimensions" => $entity->getId()));
        return $attachments;
    }

    private function saveAttachmentsFilesInternal($entity, $post, &$attachmentsFromDb) {
        $blankAttachmentIndexes = array();
        $postedAttachments = $post["attachments"];

        $entityAttachments = $entity->getAttachments();
        if ($entityAttachments) {
            foreach ($entityAttachments as $index => $attachment) {
                if ($this->isValidAttachment($postedAttachments[$index])) {
                    $imageFileData = $postedAttachments[$index]["filename"];

                    if (!empty($imageFileData['tmp_name'])) {
                        $this->deleteAttachmentsFile($attachment->getFile());
                        $attachment->setFile($this->saveFile($imageFileData, 1500, $attachment->getFile()));
                    }

                    $attachment->setOwnedBy($entity);
                    $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
                }
                else {
                    array_push($blankAttachmentIndexes, $index);
                }
            }
            foreach ($blankAttachmentIndexes as $index) {
                $entityAttachments->remove($index);
            }
        }
    }

    private function isValidAttachment($attachmentPost) {
        $hasFileData = array_key_exists("filename", $attachmentPost);

        if ($hasFileData) {
            $isSetNow = !empty($attachmentPost["filename"]["tmp_name"]);
            $wasSetBefore = !empty($attachmentPost['id']);
            $wasRemoved = $attachmentPost["removed_image"] === "1" && !$isSetNow;
            return !$wasRemoved && ($isSetNow || $wasSetBefore);
        }
        else {
            return false;
        }
    }

    private function removeAttachmentFromArray(&$attachmentsFromDb, $attachment) {
        foreach ($attachmentsFromDb as $i => $attachmentFromDb) {
            if ($attachmentFromDb->getId() == $attachment->getId()) {
                array_splice($attachmentsFromDb, $i, 1);
                break;
            }
        }
    }

    private function saveFile($file, $limit, $fileImage) {
        if (!empty($file['tmp_name'])) {
            if ($fileImage != null)
                $this->deleteAttachmentsFile($fileImage);

            $fileInfo = pathinfo($file['name']);
            if (Image::hasImageFormat($file['name'])) {
                $image = new Image();

                return $image->resizeImage(
                    $file['tmp_name'], $limit, 'public/content/weight-and-dimensions/' .
                    $file['name']);
            } else {
                $dirPath = './public/content/weight-and-dimensions/';
                $fileName = sha1($fileInfo['filename'] . time()) . '.' . $fileInfo['extension'];
                copy($file['tmp_name'], $dirPath . $fileName);

                return $fileName;
            }
        }
        return null;
    }

    private function deleteAttachmentsFile($fileName) {
        if (Image::hasImageFormat($fileName)) {
            $image = new Image();
            $image->deleteImage('public/content/weight-and-dimensions/' . $fileName);
        } else {
            $filePath = './public/content/weight-and-dimensions/' . $fileName;
            unlink($filePath);
        }
    }


    private function deleteAttachments($attachments) {
        foreach ($attachments as $attachment) {
            if (Image::hasImageFormat($attachment->getFile())) {
                $image = new Image();
                $image->deleteImage('public/content/weight-and-dimensions/' . $attachment->getFile());
            } else {
                $filePath = './public/content/weight-and-dimensions/' . $attachment->getFile();
                unlink($filePath);
            }

            $this->remove($attachment);
        }
    }


    public function getCarrierWeightAttachmentRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierWeightAttachment');
    }

    public function getCarrierDimensionsAttachmentRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\CarrierDimensionsAttachment');
    }

    public function savePostedData($weightAndDimensions, $post) {
            $this->saveAttachmentFiles($weightAndDimensions, $post);
            $this->persist($weightAndDimensions);
    }
}