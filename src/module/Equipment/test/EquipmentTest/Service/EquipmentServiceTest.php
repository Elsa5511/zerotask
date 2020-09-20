<?php

namespace EquipmentTest\Service;

use EquipmentTest\BaseSetUp;
use Application\Utility\ServiceMessage;

class EquipmentServiceTest extends BaseSetUp {

    public function testDeleteEquipmentWithNoRelated() {
        // input
        $equipmentId = 1;
        $equipment = new \Equipment\Entity\Equipment();
        $equipment->setEquipmentId($equipmentId);

        // arrangement / asserts
        $repositoryMock = $this->getRepositoryEquipmentMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue($equipment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($equipment))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        $equipmentService = $this->getEquipmentService($entityManagerMock);
        $serviceMessage = $equipmentService->deleteEquipment($equipmentId);
        $this->assertEquals($serviceMessage->getMessageType(), ServiceMessage::TYPE_SUCCESS);
    }

    /**
     * @expectedException \Application\Service\CannotDeleteException
     */
    public function testDeleteEquipmentWithRelations() {
        // input
        $equipmentId = 1;
        $equipment = new \Equipment\Entity\Equipment();
        $equipment->setEquipmentId($equipmentId);

        // arrangement / asserts
        $repositoryMock = $this->getRepositoryEquipmentMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue($equipment));

        $repositoryMock->expects($this->once())
                ->method('equipmentHasAttachments')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue(true));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $equipmentService = $this->getEquipmentService($entityManagerMock);
        $equipmentService->deleteEquipment($equipmentId);
    }




    private function getEquipmentService($entityManagerMock = null) {
        $equipmentService = new \Equipment\Service\EquipmentService(array(
            'entity_manager' => $entityManagerMock,
            'dependencies' => array(
                'translator' => $this->getApplicationServiceLocator()->get('translator')
            )
        ));
        return $equipmentService;
    }



    private function getRepositoryEquipmentMock() {
        return $this->getMockBuilder('\Equipment\Repository\Equipment')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getEntityManagerMock($repositoryMock = null) {
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
