<?php

namespace BestPractice\Service;

use Application\Service\AbstractBaseService;
use BestPractice\Entity\BestPractice;

/**
 * 
 *
 * @author cristhian.gonzales@sysco.no
 */
class BestPracticeService extends AbstractBaseService {

    const PATH_IMAGE_FOLDER = "./public/content/best-practice/";
    const SLIDE_BEST_PRACTICE_WIDTH = 870;

    /**
     * 
     * @param integer $equipmentId
     * @throws EntityDoesNotExistException
     * @return \BestPractice\Entity\BestPractice
     */
    public function getNewBestPractice($equipmentId) {
        $bestPractice = new BestPractice();
        $equipment = $this->getEquipmentRepository()->find($equipmentId);

        if ($equipment) {
            $bestPractice->setEquipment($equipment);
        } else {
            $exceptionMessage = $this->translate('Equipment does not exist.');
            $this->displayEntityNotExistException($exceptionMessage);
        }

        return $bestPractice;
    }

    /**
     * Persist
     * 
     * @param \BestPractice\Entity\BestPractice $bestPractice
     * @return \BestPractice\Entity\BestPractice
     */
    public function persistData(BestPractice $bestPractice) {
        $identifier = $bestPractice->getIdentifier();
        if (empty($identifier)) {
            $identifier = $this->generateIdentifier();
            $bestPractice->setIdentifier($identifier);
        }

        $today = new \DateTime("now");
        $bestPractice->setRevisionDate($today);

        $this->persist($bestPractice);
        return $bestPractice->getBestPracticeId();
    }

    /**
     * 
     * @param integer $equipmentId
     * @return ArrayOfBestPractice
     */
    public function getLastRevisionsByEquipment($equipmentId) {
        $bestPractices = $this->getEntityRepository()
                ->getLastRevisionsByEquipment($equipmentId);

        return $bestPractices;
    }

    /**
     * 
     * @param string $identifier
     * @return \BestPractice\Entity\BestPractice|null
     */
    public function getLastRevisionByIdentifier($identifier) {
        return $this->getEntityRepository()
                        ->findOneBy(array("identifier" => $identifier), array("revisionDate" => "DESC"));
    }

    /**
     * 
     * @param \BestPractice\Entity\BestPractice $revision
     * @return boolean
     */
    public function isLastRevision(BestPractice $revision) {
        $lastRevision = $this->getLastRevisionByIdentifier($revision->getIdentifier());
        if ($lastRevision && $lastRevision->getBestPracticeId() == $revision->getBestPracticeId())
            return true;
        return false;
    }

    /**
     * 
     * @param integer $bestPracticeId
     * @return string
     */
    public function deleteBestPractice($bestPracticeId, $attachmentService) {
        $bestPractice = $this->findById($bestPracticeId);

        if ($bestPractice !== null) {
            $revisions = $this->getEntityRepository()
                    ->findBy(array("identifier" => $bestPractice->getIdentifier()));

            $this->getSubscriptionRepository()
                    ->deleteSubscribersByIdentifier($bestPractice->getIdentifier());
            
            if (is_array($revisions) && count($revisions) > 0) {
                $this->deleteRevisions($revisions, $attachmentService);
                return count($revisions);
            }
        }
        else {
            throw new \Application\Service\EntityDoesNotExistException($this->translate('Could not delete best practice.'));
        }
    }
    
    private function deleteRevisions($revisions, $attachmentService){
        foreach ($revisions as $revision) {
            $this->deleteAttachmentsFromBestPractice($revision, $attachmentService);
            //This foreach include the $bestPractice as an item
            $this->removeRevision($revision);
        }

        $this->getEntityManager()->clear();
    }
    
    private function deleteAttachmentsFromBestPractice(BestPractice $bestPractice, $attachmentService){
        $attachments = $this->getAllAttachments($bestPractice->getBestPracticeId());
        if($attachments){
            $attachmentIds = $this->getArrayOfSpecificGetter($attachments, 'getAttachmentId');
            $attachmentService->deleteByIds($attachmentIds);
        }
    }

    /**
     * 
     * @param string $identifier
     * @return array | null
     */
    public function getOldRevisions($identifier) {
        $revisions = $this->getEntityRepository()
                ->findBy(array("identifier" => $identifier), array("revisionDate" => "DESC"));
        if (!empty($revisions)) {
            array_shift($revisions);
        }

        return $revisions;
    }

    private function removeRevision($revision) {
        $this->removeImage($revision->getFeaturedImage(), self::PATH_IMAGE_FOLDER);
        $this->removeSlides($revision->getSlides(), self::PATH_IMAGE_FOLDER);
        parent::remove($revision);
    }

    private function removeSlides($slides, $folder) {
        if (is_array($slides) && count($slides) > 0) {
            foreach ($slides as $slide) {
                $this->removeImage(\trim($slide), $folder);
            }
        }
    }

    private function generateIdentifier() {
        return sha1(time());
    }

    public function copyAttachments($bestPracticeNewRevision, $previousBestPracticeId, $bestPracticeAttachmentService) {
        $attachments = $this->getAllAttachments($previousBestPracticeId);
        $entityManager = $this->getEntityManager();
        
        foreach ($attachments as $attachment) {
            $attachmentForNewRevision = clone $attachment;
            $duplicateAttachmentFile = $bestPracticeAttachmentService->createDuplicateAttachmentFile($attachment->getFile());
            $attachmentForNewRevision->setFile($duplicateAttachmentFile);
            $attachmentForNewRevision->setBestPractice($bestPracticeNewRevision);
            $entityManager->persist($attachmentForNewRevision);
        }
        $entityManager->flush();
    }

    /**
     * 
     * @param \BestPractice\Entity\BestPractice $bestPractice
     * @param type $currentFeaturedImage
     */
    public function manageFeaturedImage(BestPractice $bestPractice, $currentFeaturedImage) {
        $featuredImage = $bestPractice->getFeaturedImage();
        $newFeatureImage = $this->getImageFromPost($featuredImage, $currentFeaturedImage, $this->image["width"]);
        $bestPractice->setFeaturedImage($newFeatureImage);
    }

    /**
     * 
     * @param \BestPractice\Entity\BestPractice $bestPractice
     */
    public function manageSlideImagesFromPost(BestPractice $bestPractice, $slideImagesFromPost) {
        $slideArray = $bestPractice->getSlides();
        $newSlideOneImage = $this->getImageFromPost($slideImagesFromPost[0], $slideArray[0]);
        $newSlideTwoImage = $this->getImageFromPost($slideImagesFromPost[1], $slideArray[1]);

        $newSlideArray = array($newSlideOneImage, $newSlideTwoImage);
        $bestPractice->setSlides($newSlideArray);
    }

    public function exportToPdf(BestPractice $bestPractice, $bestPracticeExporter) {
        $imageUrlArray = array();
        foreach ($bestPractice->getValidSlides() as $imageName) {
            array_push($imageUrlArray, getcwd() . '/public/content/best-practice/' . $imageName);
        }
        $bestPracticeExporter->export($bestPractice, $imageUrlArray);
    }

    public function getAllAttachments($bestPracticeId) {
        $criteria = array(
            'bestPractice' => $bestPracticeId,
        );
        $attachments = $this->getAttachmentRepository()->findBy($criteria);
        return $attachments;
    }

    public function findProcedures($bestPracticeId) {
        $attachmentTaxonomy = $this->getAttachmentTaxonomyRepository()
                ->findOneBy(array('type' => 'procedures'));
        return $this->findAttachments($bestPracticeId, $attachmentTaxonomy->getAttachmentTaxonomyId());
    }

    public function findUserManual($bestPracticeId) {
        $attachmentTaxonomy = $this->getAttachmentTaxonomyRepository()
                ->findOneBy(array('type' => 'user-manual'));
        return $this->findAttachments($bestPracticeId, $attachmentTaxonomy->getAttachmentTaxonomyId());
    }

    public function findAdditionalInfo($bestPracticeId) {
        $attachmentTaxonomy = $this->getAttachmentTaxonomyRepository()
                ->findOneBy(array('type' => 'additional-info'));
        return $this->findAttachments($bestPracticeId, $attachmentTaxonomy->getAttachmentTaxonomyId());
    }

    private function findAttachments($bestPracticeId, $attachmentTaxonomyId) {
        $criteria = array(
            'bestPractice' => $bestPracticeId,
            'attachmentTaxonomy' => $attachmentTaxonomyId
        );
        $attachments = $this->getAttachmentRepository()->findBy($criteria);
        return $attachments;
    }

    /**
     * 
     * @param array|String $imageFromPost
     * @param String $currentImage
     * @param integer $width
     * @return String|null
     */
    private function getImageFromPost($imageFromPost, $currentImage, $width = self::SLIDE_BEST_PRACTICE_WIDTH) {
        $isRemovedFeaturedImage = "" === $imageFromPost;
        if ($isRemovedFeaturedImage) {
            // TODO Verify or not If uploaded images are the same for the 3 fields
            //$this->removeImage($currentImage, self::PATH_IMAGE_FOLDER); 
            return null;
        } else {
            $newImageIsUploaded = is_array($imageFromPost) && !empty($imageFromPost['tmp_name']);
            if ($newImageIsUploaded) {
                $resizedImage = $this->resizeImage($imageFromPost, $width);
                return $resizedImage;
            } else {
                
            }
        }
        return $currentImage;
    }

    /**
     * 
     * @param array $imageData
     * @param int $imageWidth
     * @return String
     */
    private function resizeImage($imageData, $imageWidth) {
        $folderPath = self::PATH_IMAGE_FOLDER;
        $image = $this->getImageUtility();
        $newImage = $image->resizeImage(
                $imageData['tmp_name'], $imageWidth, $folderPath . $imageData['name']);
        return $newImage;
    }
    
    /**
     * Get an array of values about specific field of an array of objects
     * @param array $rows
     * @param string $getter
     * @return array
     */
    private function getArrayOfSpecificGetter($rows, $getter) {
        $data = array();
        foreach ($rows as $row) {
            if (method_exists($row, $getter)) {
                array_push($data, $row->$getter());
            }
        }
        return $data;
    }

    /**
     * 
     * @return \BestPractice\Repository\BestPracticeRepository
     */
    protected function getEntityRepository() {
        return $this->getRepository('BestPractice\Entity\BestPractice');
    }

    protected function getAttachmentRepository() {
        return $this->getRepository('BestPractice\Entity\BestPracticeAttachment');
    }

    protected function getAttachmentTaxonomyRepository() {
        return $this->getEntityManager()
                        ->getRepository('BestPractice\Entity\AttachmentTaxonomy');
    }

    protected function getEquipmentRepository() {
        return $this->getRepository('Equipment\Entity\Equipment');
    }

    protected function getSubscriptionRepository() {
        return $this->getEntityManager()
                        ->getRepository('BestPractice\Entity\Subscription');
    }

}
