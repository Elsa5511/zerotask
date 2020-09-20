<?php

namespace Documentation\Repository;

class InlineSectionRepository extends \Application\Repository\SectionRepository
{
    public function hasContent(\Application\Entity\Section $section) {
        $attachmentsRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\InlineSectionAttachment');
        $attachments = $attachmentsRepository->findByInlineSection($section);
        $hasContent = count($attachments) > 0;
        return $hasContent;
    }
}
