<?php

namespace BestPractice\Service;

use Application\Service\AbstractBaseService;
use BestPractice\Entity\BestPractice;
use Application\Entity\User;

class SubscriptionCronService extends AbstractBaseService
{
    
    public function notifyNewRevisionsToSubscribers(){
        $subscriptions = $this->getEntityRepository()->getSubscriptionsToNotify();
        
        if(!empty($subscriptions)){
            foreach($subscriptions as $subscription){
                $subject = "Vidum - System | " . $this->translate("New revision notification for Best Practice");
                $lastBestPracticeRevision = $this->getBestPracticeRepository()
                        ->find($subscription->getPendingNotificationId());
                $user = $subscription->getUser();
                $templateParams = $this->setTemplateParams($lastBestPracticeRevision, $user);
                $userEmail = $user->getEmail();
                
                $this->getDependency('mail_service')
                        ->sendMessage($userEmail, $subject, $templateParams);
                
                $subscription->setPendingNotificationId(0);
                $this->persist($subscription);
            }
        }
    }
    
    /**
     * 
     * @param \BestPractice\Entity\BestPractice $bestPractice
     * @param \Application\Entity\User $user
     * @return array
     */
    private function setTemplateParams(BestPractice $bestPractice, User $user)
    {
        $title = $bestPractice->getTitle();
        $subtitle = $bestPractice->getSubtitle();

        $params = array(
            'title' => $title,
            'subtitle' => $subtitle,
            'bestPracticeId' => $bestPractice->getBestPracticeId(),
            'userFullname' => $user->getDisplayName(),
        );

        $templateParams = array(
            'source' => 'template/notification/notification-new-revision',
            'params' => $params,
            'map' => $this->templateMap
        );
        return $templateParams;
    }
    
    /**
     * 
     * @return \BestPractice\Repository\SubscriptionRepository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()
                    ->getRepository('BestPractice\Entity\Subscription');
    }
    
    /**
     * 
     * @return \BestPractice\Repository\BestPracticeRepository
     */
    protected function getBestPracticeRepository()
    {
        return $this->getEntityManager()
                    ->getRepository('BestPractice\Entity\BestPractice');
    }
    
    /**
     * 
     * @return \Application\Repository\UserRepository
     */
    protected function getUserRepository()
    {
        return $this->getEntityManager()
                    ->getRepository('Application\Entity\User');
    }
    
}
