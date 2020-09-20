<?php

namespace DocumentationTest\Service;

use DocumentationTest\BaseSetUp;

class HtmlContentTest extends BaseSetUp
{

    private function createHtmlContentEntity($entityHtmlContentNameSpace, $entityName)
    {
        $sectionId = 1;
        $entityNameSpace = "\Documentation\Entity\\" . $entityName;
        $section = new $entityNameSpace();
        $section->setSectionId($sectionId);

        $htmlContentSection = new $entityHtmlContentNameSpace();
        $htmlContentSection->setDateAdd('NOW');
        $htmlContentSection->setDateUpdate('NOW');
        $method = 'set' . $entityName;
        $htmlContentSection->$method($section);
        return $htmlContentSection;
    }

    private function saveNewHtmlContent($entityName, $entityHtmlContentNameSpace)
    {
        $htmlContentSection = $this->createHtmlContentEntity($entityHtmlContentNameSpace, $entityName);

        $htmlContent = '<p>hello wolrd!</p>';

        $repositoryMock = $this->getRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($htmlContentSection));
        $result = $this->getHtmlContentService($entityManagerMock, $repositoryMock)->saveHtmlContent($htmlContentSection, $htmlContent);
        $this->assertEquals($result['namespace'], 'success');
    }

    public function testSaveNewHtmlContent()
    {
        $entitiesNames = array('InlineSection', 'DocumentationSection');
        foreach ($entitiesNames as $entityName) {
            $this->saveNewHtmlContent($entityName, "\Documentation\Entity\HtmlContent" . $entityName);
            $this->getHtmlContent($entityName, "\Documentation\Entity\HtmlContent" . $entityName);
        }
    }

    private function getHtmlContent($entityName, $entityHtmlContentNameSpace)
    {
        $sectionId = 1;
        $criteria = array(lcfirst($entityName) => $sectionId);

        $htmlContentSection = $this->createHtmlContentEntity($entityHtmlContentNameSpace, $entityName);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findOneBy')
                ->with($this->equalTo($criteria))
                ->will($this->returnValue($htmlContentSection));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getHtmlContentService($entityManagerMock, $repositoryMock)->getHtmlContent($criteria);
        $this->assertEquals($htmlContentSection, $result);
    }

    private function getHtmlContentService($entityManagerMock, $repositoryMock)
    {
        $htmlContentService = new \Documentation\Service\HtmlContentService(
                array(
            'entity_manager' => $entityManagerMock,
            'html_content_repository' => $repositoryMock
                )
        );
        return $htmlContentService;
    }

    private function getRepositoryMock()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getEntityManagerMock($repositoryMock)
    {
        $entityManagerMock = $this->getMockBuilder(
                        '\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();

        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}