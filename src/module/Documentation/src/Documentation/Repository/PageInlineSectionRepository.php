<?php

namespace Documentation\Repository;

class PageInlineSectionRepository extends \Application\Repository\SectionRepository
{
    public function hasContent(\Application\Entity\Section $section) {
        $attachmentsRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\PageInlineSectionAttachment');
        $attachments = $attachmentsRepository->findByPageInlineSection($section);
        $hasContent = count($attachments) > 0;
        return $hasContent;
    }
}
