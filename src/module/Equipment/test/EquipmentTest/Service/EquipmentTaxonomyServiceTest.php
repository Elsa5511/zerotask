<?php

namespace EquipmentTest\Service;

use Equipment\Entity\EquipmentTaxonomy;
use EquipmentTest\BaseSetUp;

class EquipmentTaxonomyServiceTest extends BaseSetUp
{

    public function testGetAvailableEquipmentTaxonomyAddService()
    {
        $availableEquipmentTaxonomiesExpected = array(
            2 => 'test'
        );

        $equipmentTaxonomyPotencial = new \Equipment\Entity\EquipmentTaxonomy;
        $equipmentTaxonomyPotencial->setEquipmentTaxonomyId(2);
        $equipmentTaxonomyPotencial->setParentId(0);
        $equipmentTaxonomyPotencial->setStatus('active');
        $equipmentTaxonomyPotencial->setName('test');

        $repositoryMock = $this->getRepositoryMock();

        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array('status'=>'active')))
                ->will($this->returnValue(array($equipmentTaxonomyPotencial)));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $equipmentInstanceResult = $this->getEquipmentTaxonomyService($entityManagerMock)
                ->getAvailableEquipmentTaxonomy(0);

        $this->assertEquals($availableEquipmentTaxonomiesExpected, $equipmentInstanceResult);
    }
    public function testGetAvailableEquipmentTaxonomyEditService()
    {
        $availableEquipmentTaxonomiesExpected = array(
            2 => 'test'
        );

        $equipmentTaxonomy = new \Equipment\Entity\EquipmentTaxonomy;
        $equipmentTaxonomy->setEquipmentTaxonomyId(1);
        $equipmentTaxonomy->setParentId(0);

        $equipmentTaxonomyPotencial = new \Equipment\Entity\EquipmentTaxonomy;
        $equipmentTaxonomyPotencial->setEquipmentTaxonomyId(2);
        $equipmentTaxonomyPotencial->setParentId(0);
        $equipmentTaxonomyPotencial->setStatus('active');
        $equipmentTaxonomyPotencial->setName('test');

        $repositoryMock = $this->getRepositoryMock();

        $repositoryMock->expects($this->once())
                ->method('find')
                ->with($this->equalTo(1))
                ->will($this->returnValue($equipmentTaxonomy));

        $repositoryMock->expects($this->once())
                ->method('fetchPotentialChildren')
                ->with($this->equalTo(1))
                ->will($this->returnValue(array($equipmentTaxonomyPotencial)));

        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $equipmentInstanceResult = $this->getEquipmentTaxonomyService($entityManagerMock)
                ->getAvailableEquipmentTaxonomy(1);

        $this->assertEquals($availableEquipmentTaxonomiesExpected, $equipmentInstanceResult);
    }

//    public function testFailDeleteByIdService()
//    {
//        $taxonomyId = 2;
//        $expectedTaxonomyMessage = array(
//            'nameSpace' => array(
//                'error'
//            ),
//            'message' => array(
//                'Category doesn\'t exist'
//            )
//        );
//
//        $repositoryMock = $this->getRepositoryMock();
//        $repositoryMock->expects($this->once())
//                ->method('find')
//                ->will($this->returnValue(false));
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//
//        $flashMessengerArray = $this->getEquipmentTaxonomyService(
//                        $entityManagerMock)->deleteById($taxonomyId);
//
//        $this->assertEquals($flashMessengerArray, $expectedTaxonomyMessage);
//        $this->assertContains($expectedTaxonomyMessage['nameSpace'][0], $flashMessengerArray['nameSpace']);
//        $this->assertContains($expectedTaxonomyMessage['message'][0], $flashMessengerArray['message']);
//    }
//
//    public function testOkDeleteByIdService()
//    {
//        $taxonomyId = 2;
//        $expectedTaxonomyMessage = array(
//            'nameSpace' => array(
//                'success'
//            ),
//            'message' => array(
//                'Category "ABC" was removed successfully'
//            )
//        );
//
//        $entityEquipmentTaxonomy = new \Equipment\Entity\EquipmentTaxonomy();
//        $entityEquipmentTaxonomy->setEquipmentTaxonomyId($taxonomyId);
//        $entityEquipmentTaxonomy->setName('ABC');
//        $repositoryMock = $this->getRepositoryMock();
//        $repositoryMock->expects($this->once())
//                ->method('find')
//                ->will($this->returnValue($entityEquipmentTaxonomy));
//
//        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);
//
//        $flashMessengerArray = $this->getEquipmentTaxonomyService(
//                        $entityManagerMock)->deleteById($taxonomyId);
//
//        $this->assertEquals($flashMessengerArray, $expectedTaxonomyMessage);
//        $this->assertContains($expectedTaxonomyMessage['nameSpace'][0], $flashMessengerArray['nameSpace']);
//        $this->assertContains($expectedTaxonomyMessage['message'][0], $flashMessengerArray['message']);
//    }

    public function testDeleteTaxonomyWithEquipments()
    {
        $taxonomyId = 2;
        $entityEquipment = new \Equipment\Entity\Equipment();
        $entityEquipment->setTitle('Equipment 1');

        $expectedEntitiesRelated = array('Equipment' => array($entityEquipment));

        $entityManagerMock = $this->setupRelationshipForTaxonomy($taxonomyId, $expectedEntitiesRelated);

        $flashMessenger = $this->getEquipmentTaxonomyService(
                        $entityManagerMock)->deleteById($taxonomyId);

        $this->assertEquals("error", $flashMessenger['namespace']);
    }
    
    public function testDeleteTaxonomyWithSubcategories()
    {
        $taxonomyId = 2;
        $entityEquipmentTaxonomyChild = new \Equipment\Entity\EquipmentTaxonomy();
        $entityEquipmentTaxonomyChild->setName('Child 1');

        $expectedEntitiesRelated = array('Subcategories' => array($entityEquipmentTaxonomyChild));

        $entityManagerMock = $this->setupRelationshipForTaxonomy($taxonomyId, $expectedEntitiesRelated);

        $flashMessenger = $this->getEquipmentTaxonomyService(
                        $entityManagerMock)->deleteById($taxonomyId);

        $this->assertEquals("error", $flashMessenger['namespace']);
    }
    
    private function setupRelationshipForTaxonomy($taxonomyId, $expectedEntitiesRelated) 
    {
        $entityEquipmentTaxonomy = new \Equipment\Entity\EquipmentTaxonomy();

        $repositoryMock = $this->getRepositoryMock();

        $repositoryMock->expects($this->once())
                ->method('find')
                ->will($this->returnValue($entityEquipmentTaxonomy));

        $repositoryMock->expects($this->exactly(1))
                ->method('getEntitiesRelated')
                ->with($this->equalTo($taxonomyId))
                ->will($this->returnValue($expectedEntitiesRelated));

        return $this->getEntityManagerMock($repositoryMock);
    }

    public function getEquipmentTaxonomyService($entityManagerMock)
    {
        $equipmentTaxonomyService = new \Equipment\Service\EquipmentTaxonomyService(
                array(
                    'entity_manager' => $entityManagerMock,
                    'dependencies' => array(
                        'translator' => $this->getApplicationServiceLocator()->get('translator')
                    )
                ));
        return $equipmentTaxonomyService;
    }

    private function getRepositoryMock()
    {
        return $this->getMockBuilder('\Equipment\Repository\EquipmentTaxonomy')
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