<?php

namespace BestPracticeTest\Service;

use BestPracticeTest\BaseSetUp;
use BestPractice\Entity\Subscription;
use BestPractice\Entity\BestPractice;
use BestPractice\Service\SubscriptionService;
use Application\Entity\User;
use Application\Utility\ServiceMessage;

class SubscriptionServiceTest extends BaseSetUp 
{
    public function testManageSubscriptionFromSubscribeAction(){
        $user = $this->getUserExample();
        $bestPractice = $this->getBestPracticeExample();
        
        $subscription = new Subscription();
        $subscription->setIdentifier($bestPractice->getIdentifier());
        $subscription->setUser($user);
        
        $userRepositoryMock = $this->getUserRepositoryMock();
        $userRepositoryMock->expects($this->once())
                ->method("find")
                ->with($this->equalTo($user->getUserId()))
                ->will($this->returnValue($user));
        
        $bestPracticeRepositoryMock = $this->getBestPracticeRepositoryMock();
        $bestPracticeRepositoryMock->expects($this->once())
                ->method("find")
                ->with($this->equalTo($bestPractice->getBestPracticeId()))
                ->will($this->returnValue($bestPractice));
        
        $repositoryMock = $this->getRepositoryMockOverrideFindBy($user->getUserId(), 
                                            $bestPractice->getIdentifier(), null);
        
        $entityManagerMock = $this->getEntityManagerMock(
                array($userRepositoryMock, $bestPracticeRepositoryMock, $repositoryMock));
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($subscription))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));
        
        // assert
        $subscriptionService = $this->getSubscriptionService($entityManagerMock);
        $result = $subscriptionService->manageSubscription($bestPractice->getBestPracticeId(), $user->getUserId());
        $expectedResult = new ServiceMessage("success", sprintf("You have subscribed to %s: %s", 
                        $bestPractice->getTitle(), $bestPractice->getSubtitle()));
        $this->assertEquals($expectedResult, $result);
    }
    
    public function testSubscribe(){       
        $bestPractice = $this->getBestPracticeExample();
        $user = $this->getUserExample();
        
        $subscription = new Subscription();
        $subscription->setIdentifier($bestPractice->getIdentifier());
        $subscription->setUser($user);
        
        $repositoryMock = $this->getRepositoryMockOverrideFindBy($user->getUserId(), 
                                            $bestPractice->getIdentifier(), null);
        
        $entityManagerMock = $this->getEntityManagerMock(array($repositoryMock));
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($subscription))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));
        
        // assert
        $subscriptionService = $this->getSubscriptionService($entityManagerMock);
        $result = $subscriptionService->subscribe($user, $bestPractice);
        $this->assertEquals(true, $result);
    }
    
    public function testUnsubscribe(){     
        $bestPractice = $this->getBestPracticeExample();
        $user = $this->getUserExample();
        
        $subscription = new Subscription();
        $subscription->setIdentifier($bestPractice->getIdentifier());
        $subscription->setUser($user);
        
        $arraySubscriptionFound = array();
        array_push($arraySubscriptionFound, $subscription);
        
        $repositoryMock = $this->getRepositoryMockOverrideFindBy($user->getUserId(), 
                                            $bestPractice->getIdentifier(), $arraySubscriptionFound);
        
        $entityManagerMock = $this->getEntityManagerMock(array($repositoryMock));
        $entityManagerMock->expects($this->once())
                ->method('remove')
                ->with($this->equalTo($subscription))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));
        
        // assert
        $subscriptionService = $this->getSubscriptionService($entityManagerMock);
        $result = $subscriptionService->unsubscribe($user, $bestPractice);
        $this->assertEquals(true, $result);
    }
    
    public function testGetSubscription(){
        $bestPractice = $this->getBestPracticeExample();
        $user = $this->getUserExample();
        
        $subscription = new Subscription();
        $subscription->setIdentifier($bestPractice->getIdentifier());
        $subscription->setUser($user);
        
        $arraySubscriptionFound = array();
        array_push($arraySubscriptionFound, $subscription);
        
        $repositoryMock = $this->getRepositoryMockOverrideFindBy($user->getUserId(), 
                                            $bestPractice->getIdentifier(), $arraySubscriptionFound);
        
        $entityManagerMock = $this->getEntityManagerMock(array($repositoryMock));
        
        // assert
        $subscriptionService = $this->getSubscriptionService($entityManagerMock);
        $result = $subscriptionService->getSubscription($user, $bestPractice);
        $this->assertEquals($subscription, $result);
    }
    
    public function testUpdateSubscribersPendingNotification(){
        $bestPractice = $this->getBestPracticeExample();
        
        $repositoryMock = $this->getSubscriptionRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('updatePendingNotificacionByIdentifier')
                ->with($bestPractice->getIdentifier(), $bestPractice->getBestPracticeId());
        $entityManagerMock = $this->getEntityManagerMock(array($repositoryMock));
        
        // assert
        $subscriptionService = $this->getSubscriptionService($entityManagerMock);
        $subscriptionService->updateSubscribersPendingNotification
                ($bestPractice->getIdentifier(), $bestPractice->getBestPracticeId());
    }
    
    
    
    /**
     * 
     * @return \BestPractice\Entity\BestPractice
     */
    private function getBestPracticeExample(){
        $bestPracticeId = 1;
        $bestPractice = new BestPractice();
        $bestPractice->setBestPracticeId($bestPracticeId);
        $bestPractice->setIdentifier("123");
        
        return $bestPractice;
    }
    
    /**
     * 
     * @return \Application\Entity\User
     */
    private function getUserExample(){
        $userId = 1;
        $user = new User();
        $user->setUserId($userId);
        
        return $user;
    }

    /**
     * 
     * @return \BestPractice\Repository\SubscriptionRepository
     */
    private function getRepositoryMockOverrideFindBy($userId, $identifier, $expectedResult){
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('findBy')
                ->with($this->equalTo(array("user" => $userId,
                            "identifier" => $identifier)))
                ->will($this->returnValue($expectedResult));
        
        return $repositoryMock;
    }
    
    /**
     * 
     * @param type $entityManagerMock
     * @return \BestPractice\Service\SubscriptionService
     */
    private function getSubscriptionService($entityManagerMock)
    {
        $subscriptionService = new SubscriptionService(array(            
            'entity_manager' => $entityManagerMock,
            'dependencies' => array(
                'translator' => $this->getApplicationServiceLocator()->get('translator'),
            )
        ));
        return $subscriptionService;
    }
    
    /**
     * 
     * @return \BestPractice\Repository\SubscriptionRepository
     */
    private function getRepositoryMock()
    {
        return $this->getMockBuilder("\Doctrine\ORM\EntityRepository")
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    /**
     * 
     * @return \BestPractice\Repository\BestPracticeRepository
     */
    private function getSubscriptionRepositoryMock()
    {
        return $this->getMockBuilder("\BestPractice\Repository\SubscriptionRepository")
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    /**
     * 
     * @return \BestPractice\Repository\BestPracticeRepository
     */
    private function getBestPracticeRepositoryMock()
    {
        return $this->getMockBuilder("\BestPractice\Repository\BestPracticeRepository")
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    /**
     * 
     * @return \Application\Repository\User
     */
    private function getUserRepositoryMock()
    {
        return $this->getMockBuilder("\Application\Repository\UserRepository")
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    private function getEntityManagerMock($arrayRepositoriesMock)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        foreach($arrayRepositoriesMock as $i => $repositoryMock){
            $entityManagerMock->expects($this->at($i))
                    ->method('getRepository')
                    ->will($this->returnValue($repositoryMock));
        }

        return $entityManagerMock;
    }
}