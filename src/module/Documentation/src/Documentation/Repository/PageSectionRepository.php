<?php

namespace Documentation\Repository;

class PageSectionRepository extends \Application\Repository\SectionRepository
{
    public function hasContent(\Application\Entity\Section $section) {
        $attachmentsRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\PageSectionAttachment');
        $attachments = $attachmentsRepository->findByPageSection($section);
        
        $pageInlineSectionRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\PageInlineSection');
        $pageInlineSections = $pageInlineSectionRepository->findByPageSection($section);
        
        $hasContent = count($attachments) > 0 || count($pageInlineSections) > 0;
        return $hasContent;
    }
}
