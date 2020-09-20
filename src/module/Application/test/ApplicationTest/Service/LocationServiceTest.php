<?php

namespace ApplicationTest\Service;

use Application\Entity\LocationTaxonomy;
use ApplicationTest\BaseSetUp;

class LocationServiceTest extends BaseSetUp {

    public function testGetSubLocations_noChildren_empty() {
        $parent = new LocationTaxonomy();
        $parent->setLocationTaxonomyId(1);

        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
            ->method('findFirstLevelChildren')
            ->with($parent->getLocationTaxonomyId())
            ->will($this->returnValue(array()));

        $service = $this->createLocationService($repositoryMock);
        $this->assertEquals(array(), $service->getSubLocations($parent));
    }

    public function testGetSubLocations_oneChild() {
        $parent = new LocationTaxonomy();
        $parent->setLocationTaxonomyId(1);
        $child = new LocationTaxonomy();
        $child->setLocationTaxonomyId(101);
        $child->setParent($parent);

        $returnValueMap = array(
            array(1, array($child)),
            array(101, array())
        );

        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->any())
            ->method('findFirstLevelChildren')
            ->will($this->returnValueMap($returnValueMap));

        $service = $this->createLocationService($repositoryMock);
        $this->assertEquals(array($child), $service->getSubLocations($parent));
    }

    public function testGetSubLocations_oneChildOneGrandchild_returnsBoth() {
        $p = new LocationTaxonomy();
        $p->setLocationTaxonomyId(1);
        $c101 = new LocationTaxonomy();
        $c101->setLocationTaxonomyId(101);
        $c101->setParent($p);
        $c10101 = new LocationTaxonomy();
        $c10101->setLocationTaxonomyId(10101);
        $c10101->setParent($c101);

        $returnValueMap = array(
            array(1, array($c101)),
            array(101, array($c10101)),
            array(10101, array())
        );

        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->any())
            ->method('findFirstLevelChildren')
            ->will($this->returnValueMap($returnValueMap));

        $service = $this->createLocationService($repositoryMock);
        $this->assertEquals(array($c101, $c10101), $service->getSubLocations($p));
    }

    public function testGetSubLocations_manySubLocations_returnsAll() {
        $p = $this->createLocationWithParent(1);
        $c101 = $this->createLocationWithParent(101, $p);
        $c102 = $this->createLocationWithParent(102, $p);
        $c10101 = $this->createLocationWithParent(10101, $c101);
        $c10102 = $this->createLocationWithParent(10102, $c101);
        $c10201 = $this->createLocationWithParent(10201, $c102);
        $c1010201 = $this->createLocationWithParent(1010201, $c10102);

        $returnValueMap = array(
            array(1, array($c101, $c102)),
            array(101, array($c10101, $c10102)),
            array(102, array($c10201)),
            array(10101, array()),
            array(10102, array($c1010201)),
            array(10201, array()),
            array(1010201, array()),
        );

        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->any())
            ->method('findFirstLevelChildren')
            ->will($this->returnValueMap($returnValueMap));

        $service = $this->createLocationService($repositoryMock);
        $this->assertEquals(6, count($service->getSubLocations($p)));
    }

    private function createLocationWithParent($id, $parent = null) {
        $location = new LocationTaxonomy();
        $location->setLocationTaxonomyId($id);
        if ($parent !== null) {
            $location->setParent($parent);
        }
        return $location;
    }

    private function createLocationService($repositoryMock) {
        $entityManager = $this->getEntityManagerMock($repositoryMock);
        return $this->getLocationService($entityManager);
    }

    public function testGetLocation() {
        // input
        $locationId = 1;
        $locationInstance = new \Application\Entity\LocationTaxonomy();

        // arrangement / asserts
        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($locationInstance));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $locationService = $this->getLocationService($entityManagerMock);

        // execute
        $locationService->getLocation($locationId);
    }

    public function testFetchAll() {
        // input
        $locationInstances = array();

        // arrangement / asserts
        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findAll')
                ->will($this->returnValue($locationInstances));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $locationService = $this->getLocationService($entityManagerMock);

        // execute
        $locationService->fetchAll();
    }

    public function testPersistData() {
        // input
        $locationId = 1;
        $location = new \Application\Entity\LocationTaxonomy();
        $location->setLocationTaxonomyId($locationId);

        // arrangement
        $repositoryMock = $this->getLocationRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($location))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $locationService = $this->getLocationService($entityManagerMock);
        // asserts
        $expectedId = $locationService->persistData($location);
        $this->assertEquals($locationId, $expectedId);
    }

    /**
     * @expectedException \Application\Service\EntityDoesNotExistException
     */
    public function testDeleteByIdWithNullLocation() {
        // input
        $locationId = 1;
        $locationNull = null;

        // arrangement / asserts
        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($locationNull));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $locationService = $this->getLocationService($entityManagerMock);
        $locationService->deleteById($locationId);
    }

    /**
     * @expectedException \Application\Service\CannotDeleteException
     */
    public function testDeleteByIdWithLocationsRelated() {
        // input
        $locationId = 1;
        $location = new \Application\Entity\LocationTaxonomy();
        $entitiesRelated = array(
            array(\Application\Service\LocationService::ALIAS_KEY_RELATIONSHIPS => 'anything')
        );

        // arrangement / asserts
        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($location));
        $repositoryMock->expects($this->once())
                ->method('getEntitiesRelated')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($entitiesRelated));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $locationService = $this->getLocationService($entityManagerMock);
        $locationService->deleteById($locationId);
    }

    public function testDeleteByIdWithNoRelated() {
        // input
        $locationId = 1;
        $location = new \Application\Entity\LocationTaxonomy();
        $entitiesRelated = array();

        // arrangement / asserts
        $repositoryMock = $this->getLocationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($location));
        $repositoryMock->expects($this->once())
                ->method('getEntitiesRelated')
                ->with($this->equalTo($locationId))
                ->will($this->returnValue($entitiesRelated));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($location))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $locationService = $this->getLocationService($entityManagerMock);
        $serviceMessage = $locationService->deleteById($locationId);
        $this->assertEquals($serviceMessage->getMessageType(), 'success');
    }

    public function getLocationService($entityManagerMock = null) {
        $equipmentInstanceService = new \Application\Service\LocationService(array(
            'entity_manager' => $entityManagerMock
        ));
        return $equipmentInstanceService;
    }

    private function getLocationRepositoryMock() {
        return $this->getMockBuilder('Application\Repository\LocationRepository')
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
