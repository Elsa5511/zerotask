<?php

namespace ApplicationTest\Service;

use ApplicationTest\BaseSetUp;
use Training\Entity\TrainingSection;
use Equipment\Entity\Equipment;

class SectionServiceTest extends BaseSetUp {

    public function testDeleteSection() {
        $sectionId = 2;
        $section = new \Training\Entity\TrainingSection();
        $section->setSectionId($sectionId);

        $result = $this->getDeleteSectionTestResult($sectionId, $section);
        $this->assertEquals($result['namespace'], 'success');
    }

    public function testCannotDeleteSectionWithSubSection() {
        // Given
        $sectionId = 2;
        $section = new TrainingSection();
        $section->setSectionId($sectionId);
        $subSectionArray = array(new TrainingSection());

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->any())
                ->method('find')
                ->with($this->equalTo($sectionId))
                ->will($this->returnValue($section));
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array('parent' => $section)))
                ->will($this->returnValue($subSectionArray));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        // When
        $sectionService = $this->getSectionService($entityManagerMock, $repositoryMock);
        $result = $sectionService->deleteSection($sectionId);

        // Then
        $this->assertEquals($result['namespace'], 'error');
    }

    public function testDeleteSectionWithContent() {
        $sectionId = 2;
        $section = new \Training\Entity\TrainingSection();
        $section->setSectionId($sectionId);
        $subSectionArray = array();
        $hasContent = true;

        $result = $this->getDeleteSectionTestResult($sectionId, $section, $subSectionArray, $hasContent);
        $this->assertEquals($result['namespace'], 'error');
    }

    public function testDeleteSectionNotExisiting() {
        $sectionId = 2;
        $section = null;

        $result = $this->getDeleteSectionTestResult($sectionId, $section);
        $this->assertEquals($result['namespace'], 'error');
    }

    private function getDeleteSectionTestResult($sectionId, $section, $subSectionArray = array(), $hasContent = false) {
        $repositoryMock = $this->getRepositoryMock(array('find', 'findByParent', 'hasContent'));

        $repositoryMock->expects($this->any())
                ->method('find')
                ->with($this->equalTo($sectionId))
                ->will($this->returnValue($section));

        $repositoryMock->expects($this->any())
                ->method('findByParent')
                ->with($this->equalTo($section))
                ->will($this->returnValue($subSectionArray));

        $repositoryMock->expects($this->any())
                ->method('hasContent')
                ->with($this->equalTo($section))
                ->will($this->returnValue($hasContent));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->any())
                ->method('remove')
                ->with($this->equalTo($section));

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->deleteSection($sectionId);
        return $result;
    }

    public function testGetParentOptionsArray() {
        $sectionId = 2;
        $section = new \Training\Entity\TrainingSection();
        $section->setSectionId($sectionId);
        $ownerFieldname = 'equipment';
        $owner = new \Equipment\Entity\Equipment();
        $possibleParents = array($section);
        $subSectionArray = array();

        $repositoryMock = $this->getRepositoryMock(array('getPossibleParents', 'findByParent'));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $repositoryMock->expects($this->any())
                ->method('findByParent')
                ->with($this->equalTo($section))
                ->will($this->returnValue($subSectionArray));

        $repositoryMock->expects($this->any())
                ->method('getPossibleParents')
                ->with($section, $ownerFieldname, $owner)
                ->will($this->returnValue($possibleParents));

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->getParentOptionsArray($ownerFieldname, $owner, $section);
        $this->assertArrayHasKey($sectionId, $result);
    }

    public function testGetParentOptionsArrayForSectionWithSubSections() {
        $sectionId = 2;
        $section = new TrainingSection();
        $section->setSectionId($sectionId);
        $ownerFieldname = 'equipment';
        $owner = new Equipment();
        $possibleParents = array($section);
        $subSectionArray = array($section);

        $repositoryMock = $this->getRepositoryMock(array('getPossibleParents', 'findByParent'));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $repositoryMock->expects($this->any())
                ->method('findBy')
                ->with($this->equalTo(array('parent' => $section)))
                ->will($this->returnValue($subSectionArray));

        $repositoryMock->expects($this->any())
                ->method('getPossibleParents')
                ->with($section, $ownerFieldname, $owner)
                ->will($this->returnValue($possibleParents));

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->getParentOptionsArray($ownerFieldname, $owner, $section);
        $this->assertArrayNotHasKey($sectionId, $result);
    }

    public function testPersistSection() {
        $sectionId = 2;
        $section = new \Training\Entity\TrainingSection();
        $section->setSectionId($sectionId);

        $repositoryMock = $this->getRepositoryMock();

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($section));

        $this->getSectionService($entityManagerMock, $repositoryMock)->persistSection($section);
    }

    public function testGetParentSections() {
        $ownerId = 2;

        $equipment = new \Equipment\Entity\Equipment();
        $equipment->setEquipmentId($ownerId);

        $parentSection1 = new \Training\Entity\TrainingSection();
        $parentSection1->setSectionId(1);
        $parentSection1->setParent(null);
        $parentSection1->setSectionOrder(1);
        $parentSection1->setEquipment($equipment);


        $parentSection2 = new \Training\Entity\TrainingSection();
        $parentSection2->setSectionId(2);
        $parentSection2->setParent(null);
        $parentSection2->setSectionOrder(2);
        $parentSection2->setEquipment($equipment);

        $expectedResult = array($parentSection1, $parentSection2);

        $filter = array('equipment' => $ownerId, 'parent' => null);
        $orderBy = array('sectionOrder' => 'ASC');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($filter, $orderBy)
                ->will($this->returnValue($expectedResult));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->getParentSections($ownerId, 'equipment');
        $this->assertEquals($result, $expectedResult);
    }

    public function testGetFirstContentSectionWhenNoSectionExists() {
        $ownerId = 2;
        $parentSections = null;

        $expectedResult = null;

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->will($this->returnValue($parentSections));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $sectionService = $this->getSectionService($entityManagerMock, $repositoryMock);
        $result = $sectionService->getFirstContentSection($ownerId, 'entity');
        $this->assertEquals($result, $expectedResult);
    }

    public function testGetFirstContentSectionWithParentContentSection() {
        $ownerId = 2;
        $parentSection1 = new \Documentation\Entity\DocumentationSection();
        $parentSection1->setSectionId(1);
        $parentSection2 = new \Documentation\Entity\DocumentationSection();
        $parentSection2->setSectionId(2);
        $parentSections = array($parentSection1, $parentSection2);

        $expectedResult = $parentSection1;

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->will($this->returnValue($parentSections));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $sectionService = $this->getSectionService($entityManagerMock, $repositoryMock);
        $result = $sectionService->getFirstContentSection($ownerId, 'equipment');
        $this->assertEquals($result, $expectedResult);
    }

    public function testGetSection() {
        $sectionId = 2;
        $section = new \Training\Entity\TrainingSection();
        $section->setSectionId($sectionId);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($sectionId))
                ->will($this->returnValue($section));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->getSection($sectionId);
        $this->assertEquals($result, $section);
    }

    public function testFetchSection() {
        $criteria = array();
        $section1 = new \Training\Entity\TrainingSection();
        $section2 = new \Training\Entity\TrainingSection();
        $sectionArray = array($section1, $section2);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo($criteria))
                ->will($this->returnValue($sectionArray));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getSectionService($entityManagerMock, $repositoryMock)->fetchSection($criteria);
        $this->assertEquals($result, $sectionArray);
    }

    private function getSectionService($entityManagerMock, $repositoryMock) {
        $sectionService = new \Application\Service\SectionService(
                array(
            'entity_manager' => $entityManagerMock,
            'section_repository' => $repositoryMock,
            'section_repository_string' => null
                )
        );
        return $sectionService;
    }

    private function getRepositoryMock($methods = array()) {
//        array_push($methods, 'setApplication');
        return $this->getMockBuilder('\Application\Repository\SectionRepository')
//        return $this->getMockBuilder('\Acl\Repository\EntityRepository')
//        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
//                        ->setMethods($methods)
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getEntityManagerMock($repositoryMock) {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}
