<?php

namespace AclTest\Service;

use AclTest\BaseSetUp;

class AclServiceTest extends BaseSetUp {

    public function testEntitiesArePersistedWithApplication() {
        // Given
        $aclEntity = $this->getMockForAbstractClass('Acl\Entity\AbstractEntity');
        $aclService = $this->getMockForAbstractClass('Acl\Service\AbstractService');

        $entityManagerMock = $this->getEntityManagerMock();
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($aclEntity);

        $aclService->setEntityManager($entityManagerMock);
        $aclService->setApplication($this->testApplication);

        // When
        $aclService->persist($aclEntity);

        // Then
        $this->assertEquals($aclEntity->getApplication(), $this->testApplication);
    }

    private function getEntityManagerMock() {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        return $entityManagerMock;
    }

}
