<?php
namespace EquipmentTest\Service;

use EquipmentTest\BaseSetUp;

class CheckoutServiceTest extends BaseSetUp
{
    public function testGetLastCheckout()
    {
        $equipmentInstanceId = null;
        $expectedCheckout = new \Equipment\Entity\Checkout();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findOneBy')
                ->with(array('equipmentInstance' => $equipmentInstanceId), array('checkoutId' => 'DESC'))
                ->will($this->returnValue($expectedCheckout));
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock);

        $result = $this->getCheckoutService($entityManagerMock)
                ->getLastCheckout($equipmentInstanceId);
        $this->assertEquals($result, $expectedCheckout);
    }

    public function testSaveAll()
    {
        // inputs
        $equipmentInstanceId = 1;
        $instanceIds = array(
            $equipmentInstanceId
        );

        $currentUserId = null;
        $checkout = new \Equipment\Entity\Checkout();
        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();

        // arrangement
        $entityManagerMock = $this->getEntityManagerForSaveAll($currentUserId, $equipmentInstanceId, $equipmentInstance);

        $checkoutService = $this->getCheckoutService($entityManagerMock);
        $resultArray = $checkoutService->saveAll($checkout, $instanceIds, $currentUserId);
        $this->assertEquals($resultArray[0]["namespace"], "success");
    }

    public function testSaveAllWithAlreadyCheckedOut()
    {
        // inputs
        $equipmentInstanceId = 1;
        $instanceIds = array(
            $equipmentInstanceId
        );
        $checkedOut = true;

        $currentUserId = null;
        $checkout = new \Equipment\Entity\Checkout();

        $equipmentInstance = new \Equipment\Entity\EquipmentInstance();
        $equipmentInstance->setCheckedOut($checkedOut);

        // arrangement
        $entityManagerMock = $this->getEntityManagerForSaveAll($currentUserId, 
                                                               $equipmentInstanceId, 
                                                               $equipmentInstance);

        $checkoutService = $this->getCheckoutService($entityManagerMock);
        $resultArray = $checkoutService->saveAll($checkout, $instanceIds, $currentUserId);
        $this->assertEquals($resultArray[0]["namespace"], "error");
    }

    private function getEntityManagerForSaveAll($currentUserId, 
                                                $equipmentInstanceId, $equipmentInstance)
    {
        $user = new \Application\Entity\User();

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->at(0))
                ->method('find')
                ->with($this->equalTo($currentUserId))
                ->will($this->returnValue($user));
        $repositoryMock->expects($this->at(1))
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
        return $entityManagerMock;
    }

    private function getCheckoutService($entityManagerMock)
    {
        $periodicControlService = new \Equipment\Service\CheckoutService(
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