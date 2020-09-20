<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/21/15
 * Time: 10:46
 */

namespace EquipmentTest\Service;

use Equipment\Service\Cache\EquipmentTaxonomyCache;
use Equipment\Service\Cache\EquipmentTaxonomyCacheService;
use EquipmentTest\BaseSetUp;

class EquipmentTaxonomyCacheServiceTest extends BaseSetUp {
    public function testGetChildren_noChildren_emptyResult() {
        $repositoryMock = $this->getRepositoryMock();
        $cacheMock = $this->getCacheMock(array());
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $service = $this->getEquipmentTaxonomyCacheService($entityManagerMock, $cacheMock);

        $this->assertEquals(array(), $service->getChildren(1));
    }

    public function testGetChildren_oneChild_oneResult() {
        $parent = new EquipmentTaxonomyCache();
        $parent->setEquipmentTaxonomyId(1);
        $child = new EquipmentTaxonomyCache();
        $child->setEquipmentTaxonomyId(101);
        $child->setParentId(1);

        $returnValueMap = array($child);

        $repositoryMock = $this->getRepositoryMock();
        $cacheMock = $this->getCacheMock($returnValueMap);
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $service = $this->getEquipmentTaxonomyCacheService($entityManagerMock, $cacheMock);

        $this->assertEquals(array($child), $service->getChildren($parent->getEquipmentTaxonomyId()));
    }

    public function testGetChildrenRecursive_noChildren_emptyResult() {
        $parent = new EquipmentTaxonomyCache();
        $parent->setEquipmentTaxonomyId(101);

        $repositoryMock = $this->getRepositoryMock();

        $cacheMock = new CacheMock();
        $cacheMock->setData(array(
            EquipmentTaxonomyCacheService::EQUIPMENT_TAXONOMY_CACHE_KEY => array()
        ));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $service = $this->getEquipmentTaxonomyCacheService($entityManagerMock, $cacheMock);

        $this->assertEmpty($service->getChildrenRecursive($parent));
    }

    public function testGetChildrenRecursive_oneChild_oneResult() {
        $parent = new EquipmentTaxonomyCache();
        $parent->setEquipmentTaxonomyId(1);
        $child = new EquipmentTaxonomyCache();
        $child->setEquipmentTaxonomyId(101);
        $child->setParentId(1);

        $returnValueMap = array($child);

        $repositoryMock = $this->getRepositoryMock();
        $cacheMock = $this->getCacheMock($returnValueMap);
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $service = $this->getEquipmentTaxonomyCacheService($entityManagerMock, $cacheMock);

        $this->assertEquals(array($child), $service->getChildrenRecursive($parent));
    }

    public function testGetChildrenRecursive_childrenHierarchy_fiveChildren() {
        $parent = new EquipmentTaxonomyCache();
        $parent->setEquipmentTaxonomyId(1);
        $c101 = new EquipmentTaxonomyCache();
        $c101->setEquipmentTaxonomyId(101);
        $c101->setParentId(1);
        $c102 = new EquipmentTaxonomyCache();
        $c102->setEquipmentTaxonomyId(102);
        $c102->setParentId(1);
        $c10101 = new EquipmentTaxonomyCache();
        $c10101->setEquipmentTaxonomyId(10101);
        $c10101->setParentId(101);
        $c10102 = new EquipmentTaxonomyCache();
        $c10102->setEquipmentTaxonomyId(10102);
        $c10102->setParentId(101);
        $c10201 = new EquipmentTaxonomyCache();
        $c10201->setEquipmentTaxonomyId(10201);
        $c10201->setParentId(102);

        $returnValueMap = array(
            $c101, $c102, $c10101, $c10102, $c10201
        );

        $repositoryMock = $this->getRepositoryMock();

        $cacheMock = $this->getCacheMock($returnValueMap);
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $service = $this->getEquipmentTaxonomyCacheService($entityManagerMock, $cacheMock);

        $this->assertEquals(5, count($service->getChildrenRecursive($parent)));
    }

    private function getCacheMock($content) {
        $cacheMock = new CacheMock();
        $cacheMock->setData(array(
            EquipmentTaxonomyCacheService::EQUIPMENT_TAXONOMY_CACHE_KEY => $content
        ));

        return $cacheMock;
    }

    public function getEquipmentTaxonomyCacheService($entityManagerMock, CacheMock $cacheMock)
    {
        $equipmentTaxonomyCacheService = new \Equipment\Service\Cache\EquipmentTaxonomyCacheService(
            array(
                'entity_manager' => $entityManagerMock,
                'dependencies' => array(
                    'translator' => $this->getApplicationServiceLocator()->get('translator'),
                    'cache' => $cacheMock
                )
            ));
        return $equipmentTaxonomyCacheService;
    }

    private function getRepositoryMock()
    {
        return $this->getMockBuilder('\Equipment\Repository\EquipmentTaxonomy')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getEntityManagerMock($repositoryMock = null)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        if ($repositoryMock != null) {
            $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));
        }

        return $entityManagerMock;
    }
}

class CacheMock {
    private $data;

    function __construct() {
        $this->data = array();
    }

    public function setData(array $data) {
        $this->data = $data;
    }

    public function hasItem($key) {
        return array_key_exists($key, $this->data);
    }

    public function setItem($key, $value) {
        $this->data[$key] = $value;
    }

    public function getItem($key) {
        return $this->data[$key];
    }
}