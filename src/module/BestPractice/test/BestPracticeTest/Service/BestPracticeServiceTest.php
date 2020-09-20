<?php

namespace BestPracticeTest\Service;

use BestPracticeTest\BaseSetUp;
use BestPractice\Entity\BestPractice;
use BestPractice\Entity\BestPracticeAttachment;
use BestPractice\Service\BestPracticeService;
use Application\Service\AttachmentService;

class BestPracticeServiceTest extends BaseSetUp {

    public function testGetNewBestPracticeSuccessfully() {
        // input
        $equipmentId = 1;

        // arrangement
        $bestPractice = new BestPractice();
        $equipment = new \Equipment\Entity\Equipment();
        $bestPractice->setEquipment($equipment);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue($equipment));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);

        // asserts
        $expectedBestPractice = $bestPracticeService->getNewBestPractice($equipmentId);
        $this->assertEquals($bestPractice, $expectedBestPractice);
    }

    /**
     * @expectedException Application\Service\EntityDoesNotExistException
     */
    public function testGetNewBestPracticeWithException() {
        // input
        $equipmentId = null;

        // arrangement
        $equipment = null;
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue($equipment));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);

        // An exception is expected
        $bestPracticeService->getNewBestPractice($equipmentId);
    }

    public function testPersistData() {
        // input
        $bestPracticeId = 1;
        $bestPractice = new BestPractice();
        $bestPractice->setBestPracticeId($bestPracticeId);

        // arrangement
        $repositoryMock = $this->getRepositoryMock();
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($bestPractice))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));

        // assert
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        $resultId = $bestPracticeService->persistData($bestPractice);
        $this->assertEquals($bestPracticeId, $resultId);
    }

    public function testGetLastRevisionsByEquipment() {
        // input
        $equipmentId = 2;

        // arrangement / asserts
        $repositoryMock = $this->getBestPracticeRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getLastRevisionsByEquipment')
                ->with($this->equalTo($equipmentId))
                ->will($this->returnValue(array()));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);

        // execute
        $result = $bestPracticeService->getLastRevisionsByEquipment($equipmentId);
        $this->assertTrue(is_array($result));
    }

    public function testGetLastRevisionByIdentifier() {
        $revision = $this->getBestPracticeExample();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findOneBy')
                ->with($this->equalTo(array('identifier' => $revision->getIdentifier())), $this->equalTo(array('revisionDate' => 'DESC')))
                ->will($this->returnValue($revision));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        $expected = $bestPracticeService->getLastRevisionByIdentifier($revision->getIdentifier());
        $this->assertEquals($revision, $expected);
    }

    public function testIsLastRevisionTrue() {
        $revision = $this->getBestPracticeExample();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findOneBy')
                ->with($this->equalTo(array('identifier' => $revision->getIdentifier())), $this->equalTo(array('revisionDate' => 'DESC')))
                ->will($this->returnValue($revision));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        $expected = $bestPracticeService->isLastRevision($revision);
        $this->assertEquals(true, $expected);
    }

    public function testDeleteBestPractice() {
        $bestPractice = $this->getBestPracticeExample();
        $bestPracticeAttachment = new BestPracticeAttachment();
        $bestPracticeAttachment->setAttachmentId(1);

        $revisions = array();
        array_push($revisions, $bestPractice);
        
        $attachments = array();
        array_push($attachments, $bestPracticeAttachment);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo($bestPractice->getBestPracticeId()))
                ->will($this->returnValue($bestPractice));

        $repositoryMock2 = $this->getRepositoryMock();
        $repositoryMock2->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array("identifier" => $bestPractice->getIdentifier())))
                ->will($this->returnValue($revisions));

        $subscriptionRepositoryMock = $this->getSubscriptionRepositoryMock();
        $subscriptionRepositoryMock->expects($this->once())
                ->method('deleteSubscribersByIdentifier')
                ->with($this->equalTo($bestPractice->getIdentifier()))
                ->will($this->returnValue(null));
        
        $repositoryAttachmentMock = $this->getRepositoryMock();
        $repositoryAttachmentMock->expects($this->once())
                ->method('findBy')
                ->will($this->returnValue($attachments));

        $entityManagerMock = $this->getEntityManagerMockManyRepositories(
                array($repositoryMock, $repositoryMock2, $subscriptionRepositoryMock,
                    $repositoryAttachmentMock));
        $entityManagerMock->expects($this->exactly(count($revisions)))
                ->method('remove')
                ->with($this->equalTo($bestPractice))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->exactly(count($revisions)))
                ->method('flush')
                ->will($this->returnValue(true));

        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        
        $attachmentService = $this->getAttachmentService();
        $attachmentService->expects($this->once())
                ->method('deleteByIds');

        // asserts
        $expectedResult = $bestPracticeService->deleteBestPractice($bestPractice->getBestPracticeId(), $attachmentService);
        $this->assertEquals(count($revisions), $expectedResult);
    }

    public function testManageFeaturedImage() {
        $featuredImage = "";
        $currentFeaturedImage = "any";
        $bestPractice = new BestPractice();
        $bestPractice->setFeaturedImage($featuredImage);

        $bestPracticeService = $this->getBestPracticeService(null);
        $bestPracticeService->manageFeaturedImage($bestPractice, $currentFeaturedImage);
    }

    public function testGetOldRevisions() {
        $bestPractice = $this->getBestPracticeExample();

        $revisions = array();
        array_push($revisions, array());
        array_push($revisions, $bestPractice);

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with(array("identifier" => $bestPractice->getIdentifier()), array("revisionDate" => "DESC"))
                ->will($this->returnValue($revisions));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        // asserts
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        $expectedResult = $bestPracticeService->getOldRevisions($bestPractice->getIdentifier());
        array_shift($revisions);
        $this->assertEquals($revisions, $expectedResult);
    }

    private function getBestPracticeService($entityManagerMock) {
        $bestPracticeService = new BestPracticeService(array(
            'entity_manager' => $entityManagerMock,
            'image' => 530,
            'dependencies' => array(
                'translator' => $this->getApplicationServiceLocator()->get('translator'),
                'imageUtility' => $this->getImageUtility()
            )
        ));
        return $bestPracticeService;
    }
    
    private function getAttachmentService() {
        return $this->getMockBuilder("\Application\Service\AttachmentService")
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    public function testExportToPdf() {
        $bestPractice = new BestPractice();
        $imageFileNames = array();
        array_push($imageFileNames, '<some_random_id>.jpg');
        $bestPractice->setSlides($imageFileNames);
        $pdfExporter = $this->getBestPracticePdfExporterMock($bestPractice, null);
        $entityManagerMock = $this->getEntityManagerMock($pdfExporter);
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);
        $bestPracticeService->exportToPdf($bestPractice, $pdfExporter);
    }

    /**
     * 
     * @return \BestPractice\Entity\BestPractice
     */
    public function getBestPracticeExample() {
        $bestPracticeId = 1;
        $bestPractice = new BestPractice();
        $bestPractice->setBestPracticeId($bestPracticeId);
        $bestPractice->setIdentifier("123");

        return $bestPractice;
    }

    public function testCopyAttachments() {
        $bestPracticeNewRevision = new BestPractice();
        $attachment1 = new \BestPractice\Entity\BestPracticeAttachment();
        $filename = 'test1.jpg';
        $attachment1->setFile($filename);
        $attachments = array($attachment1);
        $previousBestPracticeId = 1;

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with(array('bestPractice' => $previousBestPracticeId))
                ->will($this->returnValue($attachments));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->callback(function ($attachmentForNewRevision) {
                            return 'test1duplicate.jpg' === $attachmentForNewRevision->getFile();
                        }));
        $entityManagerMock->expects($this->once())
                ->method('flush');

        $this->getBestPracticeService($entityManagerMock);
        $bestPracticeAttachmentServiceMock = $this->getMockBuilder('Application\Service\AttachmentService')
                ->disableOriginalConstructor()
                ->getMock();
        $bestPracticeAttachmentServiceMock->expects($this->once())
                ->method('createDuplicateAttachmentFile')
                ->with($filename)
                ->will($this->returnValue('test1duplicate.jpg'));
        $bestPracticeService = $this->getBestPracticeService($entityManagerMock);

        $bestPracticeService->copyAttachments($bestPracticeNewRevision, $previousBestPracticeId, $bestPracticeAttachmentServiceMock);
    }

    private function getBestPracticeRepositoryMock() {
        return $this->getMockBuilder('\BestPractice\Repository\BestPracticeRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getSubscriptionRepositoryMock() {
        return $this->getMockBuilder('\BestPractice\Repository\SubscriptionRepository')
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getRepositoryMock() {
        return $this->getMockBuilder("\Doctrine\ORM\EntityRepository")
                        ->disableOriginalConstructor()
                        ->getMock();
    }

    private function getImageUtility() {
        $imageMock = $this->getMockBuilder("\Application\Utility\Image")
                ->disableOriginalConstructor()
                ->getMock();

        $imageMock->expects($this->any())
                ->method("resizeImage")
                ->will($this->returnValue("any"));

        return $imageMock;
    }

    private function getBestPracticePdfExporterMock($bestPractice) {
        $pdfExporterMock = $this->getMockBuilder("BestPractice\Service\BestPracticeExporter")
                ->disableOriginalConstructor()
                ->getMock();
        $pdfExporterMock->expects($this->once())
                ->method("export")
                ->with($this->equalTo($bestPractice), $this->callback(function($imageUrlArray) {
                            return $this->imageHasCorrectUrl($imageUrlArray);
                        }))
                ->will($this->returnValue("any"));

        return $pdfExporterMock;
    }

    private function imageHasCorrectUrl($imageUrlArray) {
        $hasCorrectUrl = true;
        foreach ($imageUrlArray as $imageUrl) {
            if (!strstr($imageUrl, '/public/content/best-practice/')) {
                $hasCorrectUrl = false;
            }
        }
        return $hasCorrectUrl;
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

    private function getEntityManagerMockManyRepositories($arrayRepositoriesMock) {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        foreach ($arrayRepositoriesMock as $i => $repositoryMock) {
            $entityManagerMock->expects($this->at($i))
                    ->method('getRepository')
                    ->will($this->returnValue($repositoryMock));
        }

        return $entityManagerMock;
    }

}
