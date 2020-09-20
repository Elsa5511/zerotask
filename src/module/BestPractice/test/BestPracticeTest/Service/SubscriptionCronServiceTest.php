<?php

namespace BestPracticeTest\Service;

use BestPracticeTest\BaseSetUp;
use BestPractice\Entity\Subscription;
use BestPractice\Entity\BestPractice;
use Application\Entity\User;
use BestPractice\Service\SubscriptionCronService;

class SubscriptionCronServiceTest extends BaseSetUp 
{
    public function testNotifyNewRevisionsToSubscribers(){
        $bestPractice = $this->getBestPracticeExample();
        $user = $this->getUserExample();
        
        $subscription = new Subscription();
        $subscription->setIdentifier($bestPractice->getIdentifier());
        $subscription->setUser($user);
        $subscription->setPendingNotificationId($bestPractice->getBestPracticeId());
        
        $arraySubscriptionFound = array();
        array_push($arraySubscriptionFound, $subscription);
        
        $repositoryMock = $this->getSubscriptionRepositoryMock();
        $repositoryMock->expects($this->once())
                ->method('getSubscriptionsToNotify')
                ->will($this->returnValue($arraySubscriptionFound));
        
        $bestPracticeRepository = $this->getBestPracticeRepositoryMock();
        $bestPracticeRepository->expects($this->once())
                ->method('find')
                ->with($this->equalTo($bestPractice->getBestPracticeId()))
                ->will($this->returnValue($bestPractice));
        
        $entityManagerMock = $this->getEntityManagerMock($repositoryMock, $bestPracticeRepository);
        $entityManagerMock->expects($this->once())
                ->method('persist')
                ->with($this->equalTo($subscription))
                ->will($this->returnValue(true));
        $entityManagerMock->expects($this->once())
                ->method('flush')
                ->will($this->returnValue(true));
        
        $mailServiceMock = $this->getMailServiceMock();
        $mailServiceMock->expects($this->once())
                ->method('sendMessage')
                ->will($this->returnValue(null));
        
        // assert
        $subscriptionCronService = $this->getSubscriptionCronService($entityManagerMock, $mailServiceMock);
        $result = $subscriptionCronService->notifyNewRevisionsToSubscribers();
        $this->assertEquals(null, $result);
    }
    
    
    
    /**
     * 
     * @return \BestPractice\Entity\BestPractice
     */
    public function getBestPracticeExample(){
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
    public function getUserExample(){
        $userId = 1;
        $user = new User();
        $user->setUserId($userId);
        
        return $user;
    }
    
    /**
     * 
     * @return \Application\Service\MailService
     */
    private function getMailServiceMock()
    {
        return $this->getMockBuilder('\Application\Service\MailService')
                        ->disableOriginalConstructor()
                        ->getMock();
    }
    
    /**
     * 
     * @param type $entityManagerMock
     * @return \BestPractice\Service\SubscriptionCronService
     */
    private function getSubscriptionCronService($entityManagerMock, $mailServiceMock)
    {
        $subscriptionCronService = new SubscriptionCronService(array(            
            'entity_manager' => $entityManagerMock,
            'templateMap' => array(
                'template/notification/notification-new-revision' => __DIR__ . '/../../../../view/template/notification/notification-new-revision.phtml',
            ),
            'dependencies' => array(
                'translator' => $this->getApplicationServiceLocator()->get('translator'),
                'mail_service' => $mailServiceMock,
            )
        ));
        return $subscriptionCronService;
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
     * @param type $repositoryMock
     * @param type $bestPracticeRepositoryMock
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManagerMock($repositoryMock, $bestPracticeRepositoryMock)
    {
        $entityManagerMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
                ->disableOriginalConstructor()
                ->getMock();
        $entityManagerMock->expects($this->exactly(2))
                ->method('getRepository')
                ->will($this->onConsecutiveCalls($repositoryMock, $bestPracticeRepositoryMock));

        return $entityManagerMock;
    }
}

?>
