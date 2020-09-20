<?php

namespace EquipmentTest\Service;

use Equipment\Entity\InstanceExpirationFieldTypes;
use EquipmentTest\BaseSetUp;
use Sysco\Aurora\Stdlib\DateTime;
use \DateInterval;

class EquipmentInstanceServiceTest extends BaseSetUp
{

    public function testGetEquipmentInstancesWithExpiredControlDate()
    {

        $equipmentInstanceId = 1;
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);

        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('getAllExpired')
                ->will($this->returnValue($equipmentInstance));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $equipmentInstanceService->getAllExpired(InstanceExpirationFieldTypes::PERIODIC_CONTROL);
    }
    
    public function testGetTotalExpiredControlDate()
    {
        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('getExpiredCount')
                ->will($this->returnValue(6));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $expiredCounts = $equipmentInstanceService
            ->getExpiredCounts(array(InstanceExpirationFieldTypes::PERIODIC_CONTROL));
        $this->assertEquals(6, $expiredCounts[InstanceExpirationFieldTypes::PERIODIC_CONTROL]);
    }

    public function testGetExpiredLifeTime()
    {

        $equipmentInstanceId = 1;
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);

        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('getAllExpired')
                ->will($this->returnValue($equipmentInstance));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $equipmentInstanceService->getAllExpired(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME);
    }
    
    public function testGetTotalExpiredLifeTime()
    {
        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('getExpiredCount')
                ->will($this->returnValue(6));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $expiredCounts = $equipmentInstanceService
            ->getExpiredCounts(array(InstanceExpirationFieldTypes::TECHNICAL_LIFETIME));
        $this->assertEquals(6, $expiredCounts[InstanceExpirationFieldTypes::TECHNICAL_LIFETIME]);
    }

    public function testUnlinkSubinstance()
    {
        // input
        $equipmentInstanceId = 1;
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);
        // arrangement / asserts
        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentInstanceId))
                ->will($this->returnValue($equipmentInstance));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist');
        $entityManagerMock->expects($this->once())
                ->method('flush');
        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $equipmentInstanceService->unlinkSubinstnace($equipmentInstanceId);
    }

    public function testUpdateManyService()
    {
        // input
        $equipmentInstanceValues = array('serialNumber' => '123456', 'regNumber' => '654321', 'visualControl' => 1);
        $equipmentInstanceIds = array(1, 2);
        $updateVisualControl = 1;

        $equipmentInstanceFirst = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstanceFirst->setEquipmentInstanceId(1);
        $equipmentInstanceFirst->setSerialNumber('without serial');

        $equipmentInstanceSecond = clone $equipmentInstanceFirst;
        $equipmentInstanceSecond->setEquipmentInstanceId(2);
        $entities = array($equipmentInstanceFirst, $equipmentInstanceSecond);

        // arrangement / asserts
        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();


        $repositoryMock->expects($this->once())
                ->method('fetchAllByIds')
                ->with($this->equalTo($equipmentInstanceIds))
                ->will($this->returnValue($entities));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
        $equipmentInstanceService->updateMany($equipmentInstanceValues, $equipmentInstanceIds, $updateVisualControl);
        $this->assertEquals($equipmentInstanceFirst->getSerialNumber(), "123456");
        $this->assertEquals($equipmentInstanceSecond->getSerialNumber(), "123456");
    }

//    public function testDeleteByIdWithNoRelated()
//    {
//        // input
//        $equipmentInstanceId = 1;
//        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
//        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);
//        $entitiesRelated = array();
//
//        // arrangement / asserts
//        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
//        $repositoryMock->expects($this->once())
//                ->method('find')
//                ->with($this->equalTo($equipmentInstanceId))
//                ->will($this->returnValue($equipmentInstance));
//
//        $repositoryMock->expects($this->once())
//                ->method('getEntitiesRelated')
//                ->with($this->equalTo($equipmentInstanceId))
//                ->will($this->returnValue($entitiesRelated));
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//        $entityManagerMock->expects($this->once())
//                ->method('remove')
//                ->with($this->equalTo($equipmentInstance))
//                ->will($this->returnValue(true));
//        $entityManagerMock->expects($this->once())
//                ->method('flush')
//                ->will($this->returnValue(true));
//
//        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
//        $resultArray = $equipmentInstanceService->deleteById($equipmentInstanceId);
//        $this->assertEquals($resultArray["namespace"], "success");
//    }

//    public function testDeleteByIdWithEquipmentIntanceRelated()
//    {
//        // input
//        $equipmentInstanceId = 1;
//        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
//        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);
//
//        $entitiesRelated = array(
//            array(\Equipment\Service\EquipmentInstanceService::ALIAS_KEY_RELATIONSHIPS => 'anything')
//        );
//
//        // arrangement / asserts
//        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();
//        $repositoryMock->expects($this->once())
//                ->method('find')
//                ->with($this->equalTo($equipmentInstanceId))
//                ->will($this->returnValue($equipmentInstance));
//
//
//        $repositoryMock->expects($this->once())
//                ->method('getEntitiesRelated')
//                ->with($this->equalTo($equipmentInstanceId))
//                ->will($this->returnValue($entitiesRelated));
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//        $equipmentInstanceService = $this->getEquipmentInstanceService($entityManagerMock);
//        $resultArray = $equipmentInstanceService->deleteById($equipmentInstanceId);
//        $this->assertEquals($resultArray["namespace"], "error");
//    }

    public function testGetEquipmentInstanceBelongEquipment()
    {
        $equipmentInstanceId = 2;
        $equipmentId = 2;

        $entityEquipmentInstanceExpected = new \Equipment\Entity\EquipmentInstance;
        $entityEquipmentInstanceExpected->setEquipmentInstanceId($equipmentInstanceId);
        $entityEquipmentInstanceExpected->setPeriodicControlDate(new DateTime("now"));

        $entityEquipment = new \Equipment\Entity\Equipment;
        $entityEquipment->setEquipmentId($equipmentId);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array('equipment' => $equipmentId)))
                ->will($this->returnValue(array($entityEquipmentInstanceExpected)));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $equipmentInstanceResult = $this->getEquipmentInstanceService($entityManagerMock)->getEquipmentInstanceBelongEquipment($equipmentId, null);
        $this->assertEquals($equipmentInstanceResult, array($entityEquipmentInstanceExpected));
    }

//    public function testGetEquipmentInstanceBelongEquipmentExpired()
//    {
//        $equipmentInstanceId = 2;
//        $equipmentId = 2;
//
//        $entityEquipmentInstanceExpected = $this->getExpiredTestEquipmentInstance($equipmentInstanceId);
//        $repositoryMock = $this->getRepositoryMock();
//
//        $repositoryMock->expects($this->once())
//                ->method('findBy')
//                ->with($this->equalTo(array('equipment' => $equipmentId)))
//                ->will($this->returnValue(array($entityEquipmentInstanceExpected)));
//
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//        $equipmentInstanceResult = $this->getEquipmentInstanceService($entityManagerMock)->getEquipmentInstanceBelongEquipment($equipmentId);
//
//        $this->assertEquals($equipmentInstanceResult[0]->getControlStatus(), 'expired');
//    }

    public function getExpiredTestEquipmentInstance($equipmentInstanceId)
    {
        $expiredDate = new DateTime('now');
        $expiredDate->sub(new DateInterval('P10D'));

        $entityEquipmentInstanceExpected = new \Equipment\Entity\EquipmentInstance;
        $entityEquipmentInstanceExpected->setEquipmentInstanceId($equipmentInstanceId);
        $entityEquipmentInstanceExpected->setPeriodicControlDate($expiredDate);
        return $entityEquipmentInstanceExpected;
    }

//    public function testGetSubinstancesByParentId()
//    {
//        $equipmentInstanceId = 2;
//
//        $entityEquipmentInstanceExpected = $this->getExpiredTestEquipmentInstance($equipmentInstanceId);
//        $repositoryMock = $this->getRepositoryMock();
//
//        $repositoryMock->expects($this->once())
//                ->method('findBy')
//                ->with($this->equalTo(array('parentId' => $equipmentInstanceId)))
//                ->will($this->returnValue(array($entityEquipmentInstanceExpected)));
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//
//        $equipmentInstanceResult = $this->getEquipmentInstanceService($entityManagerMock)->getSubinstancesByParentId($equipmentInstanceId);
//        $this->assertEquals($equipmentInstanceResult, array($entityEquipmentInstanceExpected));
//        $this->assertEquals($equipmentInstanceResult[0]->getControlStatus(), 'expired');
//    }

//    public function testGetControlTemplateWhenCategoryHasControlTemplate()
//    {
//        $expectedControlTemplate = new \Equipment\Entity\ControlTemplate;
//        $expectedControlTemplate->setName('AssignedToCategory');
//
//        $this->parentCategoryControlTemplate = null;
//
//        $controlTemplateResult = $this->getControlTemplateTestResult($expectedControlTemplate);
//        $this->assertEquals($controlTemplateResult, $expectedControlTemplate);
//    }
//
//    public function testGetControlTemplateWhenParentCategoryHasControlTemplate()
//    {
//        $expectedControlTemplate = new \Equipment\Entity\ControlTemplate;
//        $expectedControlTemplate->setName('AssignedToCategory');
//
//        $this->parentCategoryControlTemplate = $expectedControlTemplate;
//
//        $controlTemplateResult = $this->getControlTemplateTestResult();
//        $this->assertEquals($controlTemplateResult, $expectedControlTemplate);
//    }
//
//    public function testGetControlTemplateWhenNoControlTemplateIsFound()
//    {
//        $expectedDefaultControlTemplate = new \Equipment\Entity\ControlTemplate;
//        $expectedDefaultControlTemplate->setName('Default');
//
//        $this->parentCategoryControlTemplate = null;
//
//        $controlTemplateResult = $this->getControlTemplateTestResult();
//        $this->assertEquals($controlTemplateResult, $expectedDefaultControlTemplate);
//    }

    private function getControlTemplateTestResult($categoryControlTemplate = null)
    {
        $category = new \Equipment\Entity\EquipmentTaxonomy;
        $category->setControlTemplate($categoryControlTemplate);
        $category->setParentId(1);

        $equipmentTaxonomy = new \Doctrine\Common\Collections\ArrayCollection();
        $equipment = new \Equipment\Entity\Equipment;
        $equipmentTaxonomy->add($category);
        $equipment->setEquipmentTaxonomy($equipmentTaxonomy);

        $equipmentInstance = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstance->setEquipment($equipment);

        $repositoryMock = $this->getRepositoryMock(array('find', 'findOneByName'));

        $repositoryMock->expects($this->any())
                ->method('find')
                ->will($this->returnCallback(array($this, 'findCategoryCallback')));

        $defaultControlTemplate = new \Equipment\Entity\ControlTemplate;
        $defaultControlTemplate->setName('Default');

        $repositoryMock->expects($this->any())
                ->method('findOneByName')
                ->will($this->returnValue($defaultControlTemplate));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $controlTemplateTestResult = $this->getEquipmentInstanceService($entityManagerMock)->getControlTemplate($equipmentInstance);
        return $controlTemplateTestResult;
    }

    public function findCategoryCallback($categoryId)
    {
        $parentCategory = new \Equipment\Entity\EquipmentTaxonomy;
        $parentCategory->setControlTemplate($this->parentCategoryControlTemplate);
        $parentCategory->setParentId(0);

        if ($categoryId > 0)
            return $parentCategory;
        else
            return null;
    }

    public function testCalculateAndSetNextControlDate()
    {
        // input
        $controlIntervalByDays = 2;
        $purchaseDate = new DateTime("1969-01-01");
        $receptionControlDate = new DateTime("1970-01-01");
        $firstTimeUsed = new DateTime("1971-01-01");

        $equipmentEntityExpected = new \Equipment\Entity\Equipment;
        $equipmentEntityExpected->setControlIntervalByDays($controlIntervalByDays);

        $equipmentInstanceExpected = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstanceExpected->setEquipment($equipmentEntityExpected);
        $equipmentInstanceExpected->setPurchaseDate($purchaseDate);
        $equipmentInstanceExpected->setReceptionControl($receptionControlDate);
        $equipmentInstanceExpected->setFirstTimeUsed($firstTimeUsed);

        $equipmentInstanceResult = $this->getEquipmentInstanceService()->calculateAndSetNextControlDate($equipmentInstanceExpected);
        $periodicControlDate = $equipmentInstanceResult->getPeriodicControlDate();

        // assert
        $expectedPeriodicControlDate = new DateTime("1971-01-03");
        $this->assertEquals($periodicControlDate, $expectedPeriodicControlDate);
    }

    public function testCalculateAndSetNextControlDateWithNoBaseDate()
    {
        // input
        $controlIntervalByDays = 2;
        $purchaseDate = null;
        $receptionControlDate = null;
        $firstTimeUsed = null;

        $equipmentEntityExpected = new \Equipment\Entity\Equipment;
        $equipmentEntityExpected->setControlIntervalByDays($controlIntervalByDays);

        $equipmentInstanceExpected = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstanceExpected->setEquipment($equipmentEntityExpected);
        $equipmentInstanceExpected->setPurchaseDate($purchaseDate);
        $equipmentInstanceExpected->setReceptionControl($receptionControlDate);
        $equipmentInstanceExpected->setFirstTimeUsed($firstTimeUsed);

        $equipmentInstanceResult = $this->getEquipmentInstanceService()->calculateAndSetNextControlDate($equipmentInstanceExpected);
        $periodicControlDate = $equipmentInstanceResult->getPeriodicControlDate();

        // assert
        $expectedPeriodicControlDate = new DateTime();
        $this->assertEquals($periodicControlDate, $expectedPeriodicControlDate);
    }

    public function testUpdateEquipmentSubinstanceService()
    {
        $subinstanceId = 2;
        $equipmentInstanceParentId = 1;

        $equipmentInstance = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstance->setEquipmentInstanceId(2);
        $equipmentInstance->setParentId(0);


        $equipmentInstanceExpected = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstanceExpected->setEquipmentInstanceId(2);
        $equipmentInstanceExpected->setParentId(1);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(2))
                ->will($this->returnValue($equipmentInstance));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($equipmentInstanceExpected));

        $this->getEquipmentInstanceService($entityManagerMock)
                ->updateEquipmentSubinstance($subinstanceId, $equipmentInstanceParentId);
    }

    public function testGetAvailableEquipmentInstanceService()
    {
        $availableEquipmentInstancesExpected = array(
            2 => 'abc ' .
            '(testEquipment)'
        );

        $equipmentInstance = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstance->setEquipmentInstanceId(1);
        $equipmentInstance->setParentId(0);

        $equipment = new \Equipment\Entity\Equipment;
        $equipment->setTitle('testEquipment');
        $equipment->setEquipmentId(100);

        $equipmentInstancePotencial = new \Equipment\Entity\EquipmentInstance;
        $equipmentInstancePotencial->setEquipmentInstanceId(2);
        $equipmentInstancePotencial->setParentId(0);
        $equipmentInstancePotencial->setSerialNumber('abc');
        $equipmentInstancePotencial->setEquipment($equipment);

        $repositoryMock = $this->getRepositoryEquipmentInstanceMock();

        $repositoryMock->expects($this->once())
                ->method('fetchPotentialChildren')
                ->with($this->equalTo(1))
                ->will($this->returnValue(array($equipmentInstancePotencial)));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $equipmentInstanceResult = $this->getEquipmentInstanceService($entityManagerMock)
                ->getAvailableEquipmentInstance($equipmentInstance);

        $this->assertEquals($availableEquipmentInstancesExpected, $equipmentInstanceResult);
    }

    /**
     * @param null $entityManagerMock
     * @return \Equipment\Service\EquipmentInstanceService
     */
    public function getEquipmentInstanceService($entityManagerMock = null)
    {
        $equipmentInstanceService = new \Equipment\Service\EquipmentInstanceService(array(
            'entity_manager' => $entityManagerMock,
            'dependencies' => array(
               // 'translator' =>null
            )
        ));
        return $equipmentInstanceService;
    }

    private function getRepositoryMock($methods = array())
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                        ->setMethods($methods)
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getRepositoryEquipmentInstanceMock()
    {
        return $this->getMockBuilder('\Equipment\Repository\EquipmentInstance')
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