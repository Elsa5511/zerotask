<?php

namespace Application\Controller;

use Application\Entity\AttachmentWithLink;

abstract class AttachmentWithLinkController extends AttachmentController {
    protected function getAttachmentForm($entity, $mode = 'add') {
        $entityPath = $this->getOwnerEntityPath();
        $formFactory = $this->getFormFactory();
        $form = $formFactory->createAttachmentWithLinkForm($entityPath, $mode);
        $form->bind($entity);

        return $form;
    }

    protected function attachmentHasFile($post, $attachment)
    {
        if(!empty($postForm['filename']['name']))
            return true;
        else {
            $attachmentFile = $attachment->getFile();
            $hasFile = !empty($attachmentFile);
            $hasFile = $hasFile && !($post['attachment_form']['removed_attachment'] == 1); //not removed
            return $hasFile;
        }
    }

    /**
     * @param AttachmentWithLink $attachment
     */
    protected function customValidationError($post, $attachment) {
        if (!($this->attachmentHasFile($post, $attachment) || $attachment->getLink())) {
            return $this->translate("The attachment must have a file or a link.");
        }
        return null;
    }

    /**
     * @param array $post
     * @param mixed $attachment
     */
    protected function customManageAttachment($post, $attachment)
    {
        $postForm = $post['attachment_form'];
        if($attachment->getAttachmentId() > 0 && //edit
            isset($postForm['removed_attachment']) && $postForm['removed_attachment'] == 1 && //removed file
            empty($postForm['filename']['name'])) { //no file uploaded
            $this->getAttachmentService()->removeAttachmentFile($attachment->getFile());
            $attachment->setFile(null);
            $attachment->setMimeType(null);
        }
    }
}