<?php

namespace EquipmentTest\Service;

use EquipmentTest\BaseSetUp;

class PeriodicoControlServiceTest extends BaseSetUp
{

    public function testGetPeriodicControl()
    {
        $periodicControlId = 2;
        $periodicControl = new \Equipment\Entity\PeriodicControl();
        $periodicControl->setPeriodicControlId($periodicControlId);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($periodicControlId))
                ->will($this->returnValue($periodicControl));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $this->getPeriodicControlService(
                $entityManagerMock)->getPeriodicControl($periodicControlId);
    }

    public function testGetLastPeriodicControl()
    {
        $equipmentInstanceId = 10;
        $equipmetInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmetInstance->setEquipmentInstanceId($equipmentInstanceId);

        $periodicControl = new \Equipment\Entity\PeriodicControl();
        $periodicControl->setPeriodicControlId(1);
        $periodicControl->setEquipmentInstance($equipmetInstance);
        $periodicControl->setControlDate(new \DateTime('NOW'));

        $periodicControlSecond = clone $periodicControl;
        $periodicControlSecond->setPeriodicControlId(2);
        $periodicControlSecond->setEquipmentInstance($equipmetInstance);
        $periodicControlSecond->setControlDate(new \DateTime('NOW'));


        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findOneBy') 
                ->with(array('equipmentInstance' => $equipmentInstanceId), array('periodicControlId' => 'DESC'))
                ->will($this->returnValue($periodicControlSecond));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getPeriodicControlService(
                        $entityManagerMock)->getLastPeriodicControl($equipmentInstanceId);
        $this->assertEquals($result, $periodicControlSecond);
    }
    
    public function testSaveAll(){
        // inputs
        $equipmentInstanceId = 1;
        $periodicControl = new \Equipment\Entity\PeriodicControl();
        $instanceIds =array(
            $equipmentInstanceId
        );
        
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstance->setEquipmentInstanceId($equipmentInstanceId);
        
        // arrangement
        $repositoryMock = $this->getRepositoryMock();//$this->getRepositoryEquipmentInstanceMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentInstanceId))
                ->will($this->returnValue($equipmentInstance));    
        
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->any())
                ->method('persist')
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->any())
                ->method('flush')
                ->will($this->returnValue(true));
        
        $periodicControlService = $this->getPeriodicControlService($entityManagerMock);
        $resultArray = $periodicControlService->saveAll($periodicControl, $instanceIds);
        $this->assertEquals($resultArray[0]["namespace"], "success");  
    }

    public function getPeriodicControlService($entityManagerMock)
    {
        $periodicControlService = new \Equipment\Service\PeriodicControlService(
                array(
            'entity_manager' => $entityManagerMock
        ));
        return $periodicControlService;
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