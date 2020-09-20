<?php

namespace Training\Repository;


class TrainingSectionRepository extends \Application\Repository\SectionRepository
{
   
    
    public function hasContent(\Application\Entity\Section $section) {
        $attachmentsRepository = $this->getEntityManager()->getRepository('\Training\Entity\TrainingSectionAttachment');
        $attachments = $attachmentsRepository->findByTrainingSection($section);
        $hasContent = count($attachments) > 0;
        return $hasContent;
    }

}
