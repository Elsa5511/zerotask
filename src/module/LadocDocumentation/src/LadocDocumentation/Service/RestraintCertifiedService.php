<?php

namespace LadocDocumentation\Service;

use Application\Utility\Image;
use Application\Service\AbstractBaseService;
use LadocDocumentation\Entity\LadocRestraintCertified;

class RestraintCertifiedService extends AbstractBaseService {

    const IMAGE_REMOVED = 1;

    public function findByDocumentation($documentationId, $type) {
        if($type == 'load')
            return $this->getEntityRepository()->findBy(array('loadDocumentation' => $documentationId));
        else
            return $this->getEntityRepository()->findBy(array('carrierDocumentation' => $documentationId));
    }

    private function resizeImage($file, $limit, $fileImage) {
        if (!empty($file['tmp_name'])) {
            $image = new Image();
            if($fileImage != null)
                $image->deleteImage('public/content/ladoc-restraint-certified/' . $fileImage);
            
            return $image->resizeImage(
                            $file['tmp_name'], $limit, 'public/content/ladoc-restraint-certified/' .
                            $file['name']);
        }
        return null;
    }

    private function saveFile($file, $limit, $existentFile)
    {
        if (!empty($file['tmp_name'])) {
            $fileInfo = pathinfo($file['name']);
            $extension = $fileInfo['extension'];
            $isImage = in_array(strtolower($extension), array('jpg', 'gif', 'jpeg', 'png'));
            if ($isImage) {
                return $this->resizeImage($file, $limit, $existentFile);
            } else {
                $dirPath = './public/content/ladoc-restraint-certified/';
                if($existentFile != null)
                    unlink($dirPath . $existentFile);

                $fileName = sha1($fileInfo['filename'] . time()) . '.' . $extension;
                copy($file['tmp_name'], $dirPath . $fileName);
            }

            return $fileName;
        }
        return null;
    }

    public function persistData($entity)
    {
        parent::persist($entity);
        return $entity->getId();
    }

    public function deleteAttachments($attachments)
    {
        foreach($attachments as $attachment) {
            $fileName = $attachment->getFile();
            if (Image::hasImageFormat($fileName)) {
                $image = new Image();
                $image->deleteImage('public/content/ladoc-restraint-certified/' . $fileName);
            } else {
                $filePath = './public/content/ladoc-restraint-certified/' . $fileName;
                unlink($filePath);
            }
            parent::remove ($attachment);
        }
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteImage($entity)
    {
        $image = new Image();
        $fileImage = $entity->getImage();
        if($fileImage != null)
            $image->deleteImage('public/content/ladoc-restraint-certified/' . $fileImage);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteIllustrationImage($entity)
    {
        $image = new Image();
        $fileImage = $entity->getIllustrationImage();
        if($fileImage != null)
            $image->deleteImage('public/content/ladoc-restraint-certified/' . $fileImage);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteImageInformation($entity)
    {
        $file = $entity->getImageInformation();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteCalculationInformation($entity)
    {
        $file = $entity->getCalculationInformation();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteAttla($entity)
    {
        $file = $entity->getAttla();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteControlList($entity)
    {
        $file = $entity->getControlList();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteRailwayCertificate($entity)
    {
        $file = $entity->getRailwayCertificate();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteRailwayCalculation($entity)
    {
        $file = $entity->getRailwayCalculation();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    /**
     * @param LadocRestraintCertified $entity
     */
    public function deleteRailwayTunellProfile($entity)
    {
        $file = $entity->getRailwayTunellProfile();
        if($file != null)
            unlink('public/content/ladoc-restraint-certified/' . $file);
    }

    protected function removeAttachmentFromArray(&$attachments, $attachment){
        foreach($attachments as $i => $att){
            if($att->getPointAttachmentId() == $attachment->getPointAttachmentId()){
                array_splice($attachments, $i, 1);
                break;
            }
        }
    }

    public function validateFormCustom($postData, $attachmentsIndex, &$error)
    {
        $customError = array( 'image' => '', 'attachments' => array(), 'new_attachments' => array() );
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

        /*if($postData['point']['removed_image'] == self::IMAGE_REMOVED && empty($postData['point']['image_file']['tmp_name'])) {
            $customError['image'] = $this->translate('The file was not detected');
        }*/

        if(isset($postData['point']['removed_attla']) &&
                $postData['point']['removed_attla'] == self::IMAGE_REMOVED &&
                empty($postData['point']['attla_file']['tmp_name'])) {
            $customError['attla'] = $this->translate('The file was not detected');
        }

        if(isset($postData['point']['removed_railway_tunell_profile']) &&
                $postData['point']['removed_railway_tunell_profile'] == self::IMAGE_REMOVED &&
                empty($postData['point']['railway_tunell_profile_file']['tmp_name'])) {
            $customError['railway_tunell_profile'] = $this->translate('The file was not detected');
        }

        if(count($customError['attachments']) > 0 || !empty($customError['image']) || !empty($customError['attla']) || !empty($customError['railway_tunell_profile'])) {
            $error = $customError;
            return false;
        }
        else
            return true;
    }

    public function getNewEntity(\LadocDocumentation\Entity\LadocDocumentation $ladocDocumentation)
    {
        $ladocRestraintCertified = new LadocRestraintCertified();

        $ladocRestraintCertified->setLadocDocumentationWithTypeChecked($ladocDocumentation);
        
        return $ladocRestraintCertified;
    }

    public function saveAttachmentsFiles(LadocRestraintCertified &$postObject, $postData)
    {
        if($postObject->getId() > 0)
            $attachmentsFromDb = $this->getAttachmentsFromDb($postObject);
        else
            $attachmentsFromDb = array();
            
        $attachmentsFromPost = $postObject->getLadocRestraintCertifiedAttachments();
        if($attachmentsFromPost) {
            if(array_key_exists('ladocRestraintCertifiedAttachments', $postData["point"])) {
                $postAttachmentsKeys = array_keys($postData["point"]["ladocRestraintCertifiedAttachments"]);
                foreach ($attachmentsFromPost as $k => $attachment) {
                    $fileData = $postData["point"]["ladocRestraintCertifiedAttachments"][$postAttachmentsKeys[$k]]["filename"];
                    if (!empty($fileData['tmp_name']))
                        $attachment->setFile($this->saveFile($fileData, 2000, $attachment->getFile()));

                    $attachment->setLadocRestraintCertified($postObject);
                    $attachment->setTitle($attachment->getDescription());
                    $this->removeAttachmentFromArray($attachmentsFromDb, $attachment);
                }
            }
        }
            
        if($postObject->getId() > 0)
            $this->deleteAttachments($attachmentsFromDb);
    }

    public function saveImage (LadocRestraintCertified $postObject, $postData)
    {
        /*if(!empty($postData['point']['image_file']['tmp_name'])) {
            $imageData = $postData["point"]["image_file"];

            $postObject->setImage($this->resizeImage($imageData, 2000, $postObject->getImage()));
        }*/

        if(!empty($postData['point']['image_information_file']['tmp_name'])) {
            $fileData = $postData["point"]["image_information_file"];

            $postObject->setImageInformation($this->saveFile($fileData, 2000, $postObject->getImageInformation()));
        }
        else if (isset($postData['point']['removed_image_information']) && $postData['point']['removed_image_information'] == self::IMAGE_REMOVED) {
            $postObject->setImageInformation(null);
            $this->deleteImageInformation($postObject);
        }

        if(!empty($postData['point']['calculation_information_file']['tmp_name'])) {
            $fileData = $postData["point"]["calculation_information_file"];

            $postObject->setCalculationInformation($this->saveFile($fileData, 2000, $postObject->getCalculationInformation()));
        }
        else if (isset($postData['point']['removed_calculation_information']) && $postData['point']['removed_calculation_information'] == self::IMAGE_REMOVED) {
            $postObject->setCalculationInformation(null);
            $this->deleteCalculationInformation($postObject);
        }

        if(!empty($postData['point']['attla_file']['tmp_name'])) {
            $fileData = $postData["point"]["attla_file"];

            $postObject->setAttla($this->saveFile($fileData, 2000, $postObject->getAttla()));
        }

        if(!empty($postData['point']['control_list_file']['tmp_name'])) {
            $fileData = $postData["point"]["control_list_file"];

            $postObject->setControlList($this->saveFile($fileData, 2000, $postObject->getControlList()));
        }
        else if (isset($postData['point']['removed_control_list']) && $postData['point']['removed_control_list'] == self::IMAGE_REMOVED) {
            $postObject->setControlList(null);
            $this->deleteControlList($postObject);
        }

        if(!empty($postData['point']['railway_certificate_file']['tmp_name'])) {
            $fileData = $postData["point"]["railway_certificate_file"];

            $postObject->setRailwayCertificate($this->saveFile($fileData, 2000, $postObject->getRailwayCertificate()));
        }
        else if (isset($postData['point']['removed_railway_certificate']) && $postData['point']['removed_railway_certificate'] == self::IMAGE_REMOVED) {
            $postObject->setRailwayCertificate(null);
            $this->deleteRailwayCertificate($postObject);
        }

        if(!empty($postData['point']['railway_calculation_file']['tmp_name'])) {
            $fileData = $postData["point"]["railway_calculation_file"];

            $postObject->setRailwayCalculation($this->saveFile($fileData, 2000, $postObject->getRailwayCalculation()));
        }
        else if (isset($postData['point']['removed_railway_calculation']) && $postData['point']['removed_railway_calculation'] == self::IMAGE_REMOVED) {
            $postObject->setRailwayCalculation(null);
            $this->deleteRailwayCalculation($postObject);
        }

        if(!empty($postData['point']['railway_tunell_profile_file']['tmp_name'])) {
            $fileData = $postData["point"]["railway_tunell_profile_file"];

            $postObject->setRailwayTunellProfile($this->saveFile($fileData, 2000, $postObject->getRailwayTunellProfile()));
        }
        else if (isset($postData['point']['removed_railway_tunell_profile']) && $postData['point']['removed_railway_tunell_profile'] == self::IMAGE_REMOVED) {
            $postObject->setRailwayTunellProfile(null);
            $this->deleteRailwayTunellProfile($postObject);
        }

        /*if(!empty($postData['point']['image_file_illustration']['tmp_name'])) {
            $imageData = $postData["point"]["image_file_illustration"];
            $postObject->setIllustrationImage($this->resizeImage($imageData, 2000, $postObject->getIllustrationImage()));
        }
        else if ($postData['point']['removed_image_illustration'] == self::IMAGE_REMOVED) {
            $postObject->setIllustrationImage(null);
            $this->deleteIllustrationImage($postObject);
        }*/
    }

    private function getAttachmentsFromDb(LadocRestraintCertified $ladocRestraintCertified){
        $attachments = $this->getAttachmentRepository()->findBy(array("ladocRestraintCertified" => $ladocRestraintCertified->getId()));
        if(!$attachments)   $attachments = array();

        return $attachments;
    }

    public function mergeWithAttachments ($post, $files)
    {
        $attachmentsIndex = "ladocRestraintCertifiedAttachments";
        if(array_key_exists($attachmentsIndex, $post["point"]) && is_array($post["point"][$attachmentsIndex])) {
            foreach($post["point"][$attachmentsIndex] as $k => $v) {
                if(isset($files["point"]) && isset($files["point"][$attachmentsIndex]) && array_key_exists($k, $files["point"][$attachmentsIndex]))
                    foreach($files["point"][$attachmentsIndex][$k] as $k2 => $v2)
                        $post["point"][$attachmentsIndex][$k][$k2] = $v2;
            }
        }

        if(isset($files['point']['image_file'])) {
            $post['point']['image_file'] = $files['point']['image_file'];
        }

        if(isset($files['point']['image_information_file'])) {
            $post['point']['image_information_file'] = $files['point']['image_information_file'];
        }

        if(isset($files['point']['calculation_information_file'])) {
            $post['point']['calculation_information_file'] = $files['point']['calculation_information_file'];
        }

        if(isset($files['point']['attla_file'])) {
            $post['point']['attla_file'] = $files['point']['attla_file'];
        }

        if(isset($files['point']['control_list_file'])) {
            $post['point']['control_list_file'] = $files['point']['control_list_file'];
        }

        if(isset($files['point']['railway_certificate_file'])) {
            $post['point']['railway_certificate_file'] = $files['point']['railway_certificate_file'];
        }

        if(isset($files['point']['railway_calculation_file'])) {
            $post['point']['railway_calculation_file'] = $files['point']['railway_calculation_file'];
        }

        if(isset($files['point']['railway_tunell_profile_file'])) {
            $post['point']['railway_tunell_profile_file'] = $files['point']['railway_tunell_profile_file'];
        }

        if(isset($files['point']['image_file_illustration'])) {
            $post['point']['image_file_illustration'] = $files['point']['image_file_illustration'];
        }

        return $post;
    }

    public function setNullDates(LadocRestraintCertified $entity, $postData)
    {
        $post = is_array($postData) ? $postData : $postData->toArray();
        if(array_key_exists('approvedDate', $post['point']) && !$post['point']['approvedDate'])
            $entity->setApprovedDate(null);
    }

    protected function getDocumentationRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocDocumentation');
    }

    protected function getAttachmentRepository()
    {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocRestraintCertifiedAttachment');
    }

    protected function getEntityRepository() {
        return $this->getEntityManager()->getRepository('LadocDocumentation\Entity\LadocRestraintCertified');
    }
}