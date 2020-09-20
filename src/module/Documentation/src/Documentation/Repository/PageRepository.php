<?php

namespace Documentation\Repository;

class PageRepository extends \Acl\Repository\EntityRepository
{

    public function hasSection(\Documentation\Entity\Page $page)
    {
        $pageSectionRepository = $this->getEntityManager()->getRepository('\Documentation\Entity\PageSection');
        $sections = $pageSectionRepository->findByPage($page);

        $hasSection = count($sections) > 0;
        return $hasSection;
    }

}
