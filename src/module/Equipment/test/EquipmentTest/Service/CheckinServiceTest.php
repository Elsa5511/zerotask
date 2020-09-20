<?php
namespace EquipmentTest\Service;

use EquipmentTest\BaseSetUp;

class CheckinServiceTest extends BaseSetUp
{
    /**
     * @group rafu
     */
    public function testPersistData()
    {
        $expectedCheckinId = 1;
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        
        $checkin = new \Equipment\Entity\Checkin();
        $checkin->setCheckinId($expectedCheckinId);
        $checkin->setEquipmentInstance($equipmentInstance);

        $currentUserId = null;
        $user = new \Application\Entity\User();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($currentUserId))
                ->will($this->returnValue($user));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->any())
                ->method('persist')
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->any())
                ->method('flush')
                ->will($this->returnValue(true));

        $checkinService = $this->getCheckinService($entityManagerMock);
        $result = $checkinService->persistData($checkin, $currentUserId);
        $this->assertEquals($result, $expectedCheckinId);
    }

    private function getCheckinService($entityManagerMock)
    {
        $service = new \Equipment\Service\CheckinService(
                    array(
                        'entity_manager' => $entityManagerMock
                    )
                );
        return $service;
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