<?php

namespace Application\Service;

use Acl\Service\AbstractService;

class SectionService extends AbstractService {

    private function getEntityRepository() {
        return $this->getRepository($this->sectionRepositoryString);
    }

    public function persistSection($section) {
        parent::persist($section);
    }

    public function deleteSection($sectionId) {
        $section = $this->getSection($sectionId);
        if (!empty($section)) {
            if ($this->hasSubSections($section)) {
                $nameSpace = "error";
                $message = $this->translate('Cannot delete a section with subsections');
            } elseif ($this->hasContent($section)) {
                $nameSpace = "error";
                $message = $this->translate('Cannot delete a section with content');
            } else {
                $this->remove($section);
                $nameSpace = "success";
                $message = $section->getLabel() . ' ' . $this->translate('has been deleted successfully');
            }
        } else {
            $nameSpace = "error";
            $message = $this->translate('Section doesn\'t exist');
        }

        return array(
            'namespace' => $nameSpace,
            'message' => $message
        );
    }

    public function getSection($sectionId) {
        return $this->getEntityRepository()->find($sectionId);
    }

    public function getInlineSections($documentationId, $ownerAttributeName) {
        $inlineSections = $this->getEntityRepository()->findBy(
                array($ownerAttributeName => $documentationId), array('sectionOrder' => 'ASC')
        );
        return $inlineSections;
    }

    public function getInlineSectionsByArray($sectionIdAttributeName, array $values)
    {
        return $this->getEntityRepository()->getInlineSectionsByArray($sectionIdAttributeName, $values);
    }

    public function getFirstContentSection($ownerId, $ownerAttributeName) {
        $parentSections = $this->getParentSections($ownerId, $ownerAttributeName);
        
        if (count($parentSections) > 0) {
            return $parentSections[0];
        } else {
            return null;
        }
    }

    public function fetchSection($criteria = array()) {
        return $this->getEntityRepository()->findBy($criteria);
    }

    /**
     * Returns the possible parent sections when adding new section
     * Enforces max one sub-level
     * 
     * @param string $ownerFieldname
     * @param Entity $owner
     * @param \Application\Entity\Section $entity
     * @return array
     */
    public function getParentOptionsArray($ownerFieldname, $owner, \Application\Entity\Section $entity) {
        $optionsArray = array();
        if (!$this->hasSubSections($entity)) {
            $possibleParents = $this->getEntityRepository()->getPossibleParents($entity, $ownerFieldname, $owner);
            foreach ($possibleParents as $parent) {
                $optionsArray[$parent->getSectionId()] = $parent->getLabel();
            }
        }
        return $optionsArray;
    }

    /**
     * This method returns all parent sections for an equipment
     * 
     * @param int $ownerId
     * @return array
     */
    public function getParentSections($ownerId, $ownerAttributeName) {
        $sectionRepository = $this->sectionRepository;
        $filter = array($ownerAttributeName => $ownerId, 'parent' => null);
        $orderBy = array('sectionOrder' => 'ASC');
        $parents = $sectionRepository->findBy($filter, $orderBy);
        return $parents;
    }

    public function searchByWords($words, $ownerFieldname, $owner) {
        $sectionRepository = $this->sectionRepository;
        return $sectionRepository->searchByWords($words, $ownerFieldname, $owner);
    }

    /**
     * Checks of a section has subsections
     * 
     * @param \Application\Entity\Section $section
     * @return boolean
     */
    private function hasSubSections(\Application\Entity\Section $section) {
        $subSections = $this->getEntityRepository()->findBy(array('parent' => $section)); //Parent($section);
        $hasSubSections = count($subSections) > 0;
        return $hasSubSections;
    }

    /**
     * Checks of a section has content
     * 
     * @param \Application\Entity\Section $section
     * @return boolean
     */
    private function hasContent(\Application\Entity\Section $section) {
        return $this->getEntityRepository()->hasContent($section);
    }

}
