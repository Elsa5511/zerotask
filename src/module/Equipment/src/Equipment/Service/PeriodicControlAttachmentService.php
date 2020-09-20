<?php

namespace Equipment\Service;

use Application\Service\AttachmentService;
use Sysco\Aurora\Stdlib\DateTime;

class PeriodicControlAttachmentService extends AttachmentService {

    const ATTACHMENTS_INDEX = 'periodicControlAttachments';

    public function getAttachmentTaxonomies()
    {
        $repository = $this->getRepository('Application\Entity\AttachmentTaxonomy');
        return $repository->findBy(array(), array('type' => 'ASC'));
    }

    public function mergeWithAttachments ($post, $files)
    {
        if(array_key_exists(self::ATTACHMENTS_INDEX, $post) && is_array($post[self::ATTACHMENTS_INDEX])) {
            foreach($post[self::ATTACHMENTS_INDEX] as $k => $v) {
                if(isset($files) && array_key_exists($k, $files[self::ATTACHMENTS_INDEX]))
                    foreach($files[self::ATTACHMENTS_INDEX][$k] as $k2 => $v2)
                        $post[self::ATTACHMENTS_INDEX][$k][$k2] = $v2;
            }
        }

        return $post;
    }

    public function saveFilesAndSetAttachments($form, $post)
    {
        $attachmentsFieldsets = $form->get(self::ATTACHMENTS_INDEX);
        if($attachmentsFieldsets && $attachmentsFieldsets->count() > 0) {
            $attachmentsFromPost = $post[self::ATTACHMENTS_INDEX];
            foreach($attachmentsFieldsets as $k => $attachmentFieldset) {
                $attachmentFromPost = $attachmentsFromPost[$k];

                $attachment = $attachmentFieldset->getObject();
                $attachment->setApplication($this->application);

                $fileData = $attachmentFromPost['filename'];
                if(!empty($fileData['name'])) {
                    $attachment->setFile($this->copyAttachmentFile($fileData));
                    $attachment->setMimeType($fileData['type']);
                }

                $attachment->setDateAdd(new DateTime('NOW'));
            }
        }
    }

}
