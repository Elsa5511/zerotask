<?php
namespace ApplicationTest\Service;

use ApplicationTest\BaseSetUp;
use Sysco\Aurora\Stdlib\DateTime;
use \DateInterval;

class ServiceTest extends BaseSetUp {

    public function testPersist() {
        // input
        $entityMock = null;
        
        // Arrangement
        $repositoryMock = $this->getRepositoryMock($entityMock);
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);       
        $serviceTest = new \Sysco\Aurora\Doctrine\ORM\Service (array(
            'entity_manager' => $entityManagerMock
        ));
        
        $entityResult = $serviceTest->persist($entityMock);        
        $this->assertEquals($entityResult, $entityMock);
    }
    
    public function testRemove() {
        // input
        $entityMock = null;
        
        // Arrangement
        $repositoryMock = $this->getRepositoryMock($entityMock);
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);       
        $serviceTest = new \Sysco\Aurora\Doctrine\ORM\Service (array(
            'entity_manager' => $entityManagerMock
        ));
    
        $entityResult = $serviceTest->remove($entityMock);
        $this->assertEquals($entityResult, $entityMock);
    }
    
    private function getRepositoryMock($entityMock) {
        $repositoryMock = $this->getMockBuilder(
            '\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
        
        $repositoryMock->expects($this->any())
            ->method('persist')
            ->with($this->equalTo($entityMock))
            ->will($this->returnValue(true));
        
        $repositoryMock->expects($this->any())
            ->method('remove')
            ->with($this->equalTo($entityMock))
            ->will($this->returnValue(true));
        
        $repositoryMock->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(true));
        
        $repositoryMock->expects($this->any())
            ->method('clear')
            ->will($this->returnValue(true));
        
        return $repositoryMock;
    }

    private function getEntityManagerMock($repositoryMock) {
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