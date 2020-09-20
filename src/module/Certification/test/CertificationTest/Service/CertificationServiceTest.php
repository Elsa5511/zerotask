<?php

namespace CertificationTest\Service;

use CertificationTest\BaseSetUp;

class CertificationServiceTest extends BaseSetUp
{

    public function testGetNewCertification()
    {
        // input
        $equipmentId = 1;
        $equipmentEntity = new \Equipment\Entity\Equipment();

        $expectedCertification = new \Certification\Entity\Certification();
        $expectedCertification->setEquipment($equipmentEntity);

        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue($equipmentEntity));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $certificationService = $this->getCertificationService($entityManagerMock);

        // execute
        $result = $certificationService->getNewCertification($equipmentId);
        $this->assertEquals($result, $expectedCertification);
    }

    public function testGetNewCertificationNull()
    {
        // input
        $equipmentId = 1;
        $equipmentEntity = null;

        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->will($this->returnValue($equipmentEntity));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $certificationService = $this->getCertificationService($entityManagerMock);

        // execute
        $result = $certificationService->getNewCertification($equipmentId);
        $this->assertNull($result);
    }
    
    public function testFindById()
    {
        // input
        $certificationId = 1;
        $expectedCertification = new \Certification\Entity\Certification();
        $expectedCertification->setCertificationId($certificationId);

        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($certificationId))
                ->will($this->returnValue($expectedCertification));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $certificationService = $this->getCertificationService($entityManagerMock);

        // execute
        $result = $certificationService->findById($certificationId);
        $this->assertEquals($certificationId, $result->getCertificationId());
    }
   

    public function testFindByEquipment()
    {
        // input
        $equipmentId = 1;
        $certificationEntityList = array();
        $criteria = array("equipment" => $equipmentId);

        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo($criteria))
                ->will($this->returnValue($certificationEntityList));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $certificationService = $this->getCertificationService($entityManagerMock);

        // execute
        $certificationService->findByEquipment($equipmentId);
    }

    public function testPersistData()
    {
        // input
        $certificationId = 1;
        $certification = new \Certification\Entity\Certification();
        $certification->setCertificationId($certificationId);

        // arrangement
        $repositoryMock = $this->getCertificationRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($certification))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $certificationService = $this->getCertificationService($entityManagerMock);
        // asserts
        $expectedId = $certificationService->persistData($certification);
        $this->assertEquals($certificationId, $expectedId);
    }

    public function testDeleteByIdWithNullCertification()
    {
        // input
        $certificationId = 1;
        $certificationNull = null;

        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($certificationId))
                ->will($this->returnValue($certificationNull));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $certificationService = $this->getCertificationService($entityManagerMock);
        $resultArray = $certificationService->deleteById($certificationId);
        $this->assertEquals($resultArray["namespace"], "error");
    }
    
    public function testDeleteById()
    {
        // input
        $certificationId = 1;
        $certification = new \Certification\Entity\Certification();
        
        // arrangement / asserts
        $repositoryMock = $this->getCertificationRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($certificationId))
                ->will($this->returnValue($certification));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($certification))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $certificationService = $this->getCertificationService($entityManagerMock);
        $resultArray = $certificationService->deleteById($certificationId);
        $this->assertEquals($resultArray["namespace"], "success");
    }

    public function getCertificationService($entityManagerMock = null)
    {
        $certificationService = new \Certification\Service\CertificationService(array(
            'entity_manager' => $entityManagerMock
        ));
        return $certificationService;
    }

    private function getCertificationRepositoryMock()
    {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    

    private function getEntityManagerMock($repositoryMock)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->any())
                ->method('getRepository')
                ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }

}