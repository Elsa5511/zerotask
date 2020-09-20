<?php
/**
 * Created by PhpStorm.
 * User: sysco
 * Date: 8/31/15
 * Time: 13:39
 */

namespace ApplicationTest\Service;

use Application\Service\Cache\LocationCacheService;
use Application\Service\Cache\LocationTaxonomyCache;
use ApplicationTest\BaseSetUp;


class LocationCacheServiceTest extends BaseSetUp {
    public function getValueOptionsByApplication_noData_emptyResult() {
        $repositoryMock = $this->getRepositoryMock();
        $cacheMock = $this->getCacheMock(array());
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $service = $this->getLocationCacheService($entityManagerMock, $cacheMock);

        $this->assertEquals(array(), $service->searchValuesByApplicationAndSlug("any"));
    }

    private function getCacheMock($content) {
        $cacheMock = new CacheMock();
        $cacheMock->setData(array(
            LocationCacheService::LOCATIONS_CACHE_KEY => $content
        ));

        return $cacheMock;
    }

    public function getLocationCacheService($entityManagerMock, CacheMock $cacheMock)
    {
        $locationCacheService = new \Application\Service\Cache\LocationCacheService(
            array(
                'entity_manager' => $entityManagerMock,
                'dependencies' => array(
                    'translator' => $this->getApplicationServiceLocator()->get('translator'),
                    'cache' => $cacheMock
                )
            ));
        return $locationCacheService;
    }

    private function getRepositoryMock()
    {
        return $this->getMockBuilder('\Application\Repository\LocationRepository')
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