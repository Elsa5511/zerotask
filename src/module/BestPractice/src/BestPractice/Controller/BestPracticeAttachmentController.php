<?php
namespace BestPractice\Controller;

use Application\Controller\AttachmentController;
use BestPractice\Entity\BestPracticeAttachment;

class BestPracticeAttachmentController extends AttachmentController
{

    protected function getCustomViewParameters()
    {
        return array(
            'controller' => 'best-practice-attachment',   
        );
    }

    public function getAttachmentService()
    {
        return $this->getService('BestPractice\Service\BestPracticeAttachmentService');
    }

    protected function getAttachmentEntityWithOwner($bestPracticeId)
    {
        $bestPractice = $this->getOwnerEntityService()->findById($bestPracticeId);
        $attachmentInstance = new BestPracticeAttachment();
        $attachmentInstance->setBestPractice($bestPractice);
        return $attachmentInstance;
    }

    protected function getOwnerEntityService()
    {
        return $this->getServiceLocator()->get(
                        'BestPractice\Service\BestPracticeService');
    }

    protected function getOwnerEntityPath()
    {
        return 'BestPractice\Entity\BestPracticeAttachment';
    }

    protected function getOwnerController()
    {
        return 'best-practice';
    }

    protected function getOwnerFieldName()
    {
        return 'best-practice';
    }
    
    protected function getAttachmentForm($entity, $mode = 'add')
    {
        $entityPath = $this->getOwnerEntityPath();
        $formFactory = $this->getFormFactory('BestPractice');
        $form = $formFactory->createAttachmentForm($entityPath, $mode);
        $form->bind($entity);

        return $form;
    }

}