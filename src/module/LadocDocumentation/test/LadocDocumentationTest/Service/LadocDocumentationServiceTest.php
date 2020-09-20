<?php

namespace LadocDocumentationTest\Service;

use Equipment\Entity\Equipment;
use LadocDocumentation\Entity\LadocDocumentation;
use LadocDocumentation\Service\LadocDocumentationService;
use LadocDocumentationTest\BaseSetUp;

class LadocDocumentationServiceTest extends BaseSetUp {
    const EQUIPMENT_ID = 99;

    public function test_createDocumentation_withEquipmentIdAndType_returnsId() {
        // Given
        $equipment = new Equipment();
        $equipment->setEquipmentId(self::EQUIPMENT_ID);
        $type = LadocDocumentation::TYPE_LOAD;

        $documentationId = 80;

        $repositoryMock = $this->b();
        $repositoryMock
            ->expects($this->any())
            ->method('find')
            ->with($this->equalTo(self::EQUIPMENT_ID))
            ->will($this->returnValue($equipment));
            //->with($this->equalTo(self::EQUIPMENT_ID))


//        $repositoryMock
//            ->expects($this->once())
//            ->method('persist')
//            ->will($this->returnArgument($documentationId));

//        $repositoryMock
//            ->expects($this->any())
//            ->method('setApplication');


        $entityManagerMock = $this->createEntityManagerMock($repositoryMock);

        $service = $this->createService($entityManagerMock);

        // When
        $documentationIdResult = $service->createDocumentation(self::EQUIPMENT_ID, $type);

        // Then
        $this->assertEquals($documentationIdResult, $documentationId);

    }


    private function createRepositoryMock() {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function a() {
        return $this->getMockBuilder('LadocDocumentation\Entity\LadocDocumentation')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function b() {
        return $this->getMockBuilder('Equipment\Entity\Equipment')
            ->disableOriginalConstructor()
            ->getMock();
    }


    private function createService($entityManagerMock) { //}, $documentationRepositoryMock, $equipmentRepositoryMock) {
        $service = new LadocDocumentationService(array(
            'entity_manager' => $entityManagerMock,
//            'quiz_repository' => $repositoryMock,
//            'dependencies' => array(
//                'translator' => null
//            )
        ));
        return $service;
    }

    private function createEntityManagerMock($repositoryMock) {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $entityManagerMock->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($repositoryMock));

        return $entityManagerMock;
    }
}