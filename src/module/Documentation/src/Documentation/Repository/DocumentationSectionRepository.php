<?php

namespace Documentation\Repository;

class DocumentationSectionRepository extends \Application\Repository\SectionRepository
{
    public function hasContent(\Application\Entity\Section $section) {
        $attachmentsRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\DocumentationSectionAttachment');
        $attachments = $attachmentsRepository->findByDocumentationSection($section);

        $inlineSectionRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\InlineSection');
        $inlineSections = $inlineSectionRepository->findByDocumentation($section);
        
        $hasContent = count($attachments) > 0 || count($inlineSections) > 0;
        return $hasContent;
    }
}
