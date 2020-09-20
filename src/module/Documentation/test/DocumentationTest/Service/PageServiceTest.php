<?php

namespace DocumentationTest\Service;

use DocumentationTest\BaseSetUp;

class PageTest extends BaseSetUp
{

    public function testDeleteByIdSuccess()
    {
        $expectedResult = 'success';

        $page = new \Documentation\Entity\Page();
        $page->setPageId(1);
        $page->setName('pageTest');
        $page->setFeaturedImage(null);


        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(1))
                ->will($this->returnValue($page));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($page);

        $entityManagerMock->expects($this->once())
                ->method('flush');

        $result = $this->getPageService($entityManagerMock)->deleteById(1);
        $this->assertEquals($result['namespace'], $expectedResult);
    }

    public function testDeleteByIdError()
    {
        $expectedResult = 'error';

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(1))
                ->will($this->returnValue(null));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        
        $result = $this->getPageService($entityManagerMock)->deleteById(1);
        $this->assertEquals($result['namespace'], $expectedResult);
    }
    public function testPersistData()
    {

        $featuredImage = 'abc.jpg';
        $postData = array(
            'featured_image' => array(
                'tmp_name' => null,
                'name' => $featuredImage
            )
        );


        $page = new \Documentation\Entity\Page();
        $page->setPageId(1);
        $page->setFeaturedImage($featuredImage);


        $repositoryMock = $this->getRepositoryMock();

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($page);
        $entityManagerMock->expects($this->once())
                ->method('flush');


        $this->getPageService($entityManagerMock)->persistData($page, $postData);
    }

    public function testListPagesByCategory()
    {
        $categoryId = 100;

        $category = new \Equipment\Entity\EquipmentTaxonomy();
        $category->setEquipmentTaxonomyId($categoryId);

        $page1 = new \Documentation\Entity\Page();
        $page1->setPageId(1);
        $page1->setCategory($category);

        $page2 = clone $page1;
        $page2->setPageId(2);

        $expectedResult = array($page1, $page2);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array('category' => $categoryId)))
                ->will($this->returnValue($expectedResult));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getPageService($entityManagerMock)->listPagesByCategory($categoryId);
        $this->assertEquals($result, $expectedResult);
    }

    public function testGetPage()
    {
        $pageId = 1;
        $page = new \Documentation\Entity\Page();
        $page->setPageId($pageId);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($pageId))
                ->will($this->returnValue($page));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getPageService($entityManagerMock)->getPage($pageId);
        $this->assertEquals($result, $page);
    }

    private function getPageService($entityManagerMock)
    {
        $pageService = new \Documentation\Service\PageService(
                array(
            'entity_manager' => $entityManagerMock,
                )
        );

        return $pageService;
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