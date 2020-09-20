<?php

namespace ApplicationTest\Service;

use ApplicationTest\BaseSetUp;

class AttachmentTest extends BaseSetUp {

    public function testDeleteByIdsSuccess() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId)
                ->setFile('test.txt');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($attachmentId))
                ->will($this->returnValue($attachment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($attachment));

        $result = $this->getAttachmentService($entityManagerMock, $repositoryMock)->deleteByIds(array($attachmentId));
        $this->assertEquals($result['deleted'], 1);
    }

    public function testDeleteByIdsFail() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId)
                ->setFile('test.txt');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->exactly(2))
                ->method('find')
                ->will($this->returnValue(null));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);


        $result = $this->getAttachmentService($entityManagerMock, $repositoryMock)->deleteByIds(array(3, 5));
        $this->assertEquals($result['fails'], 2);
    }

    public function testDeleteByIdsFailAndSuccess() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId)
                ->setFile('test.txt');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->at(0))
                ->method('find')
                ->will($this->returnValue(null));
        $repositoryMock->expects($this->at(1))
                ->method('find')
                ->will($this->returnValue($attachment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($attachment));

        $result = $this->getAttachmentService($entityManagerMock, $repositoryMock)->deleteByIds(array(3, 2));
        $this->assertEquals($result['fails'], 1);
        $this->assertEquals($result['deleted'], 1);
    }

    public function testDeleteAttachmentService() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId)
                ->setFile('test.txt');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($attachmentId))
                ->will($this->returnValue($attachment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($attachment));

        $result = $this->getAttachmentService($entityManagerMock, $repositoryMock)->deleteAttachment($attachmentId);
        $this->assertEquals($result, true);
    }

    public function testPersistAttachmentService() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId);

        $repositoryMock = $this->getRepositoryMock();


        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($attachment));

        $this->getAttachmentService($entityManagerMock, $repositoryMock)->persistAttachment($attachment);
    }

    public function testGetAttachment() {
        $attachmentId = 2;
        $attachment = new \Equipment\Entity\EquipmentAttachment();
        $attachment->setAttachmentId($attachmentId);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($attachmentId))
                ->will($this->returnValue($attachment));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $this->getAttachmentService($entityManagerMock, $repositoryMock)->getAttachment($attachmentId);
    }

    private function getAttachmentService($entityManagerMock, $repositoryMock) {
        $attachmentService = new \Application\Service\AttachmentService(
                array(
            'entity_manager' => $entityManagerMock,
            'attachment_repository' => $repositoryMock,
            'attachment_repository_string' => null,
                )
        );
        return $attachmentService;
    }

    private function getRepositoryMock() {
        return $this->getMockBuilder('\Doctrine\ORM\EntityRepository')
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
