<?php

namespace BestPractice\Service;

use Application\Service\AbstractBaseService;
use BestPractice\Entity\Subscription;
use Application\Utility\ServiceMessage;

class SubscriptionService extends AbstractBaseService
{
    /**
     * 
     * @param int $bestPracticeId
     * @param int $userId
     * @param bool $subscribe
     * @return \Application\Utility\ServiceMessage
     */
    public function manageSubscription($bestPracticeId, $userId, $subscribe = true) {
        $user = $this->getUserRepository()->find($userId);
        $bestPractice = $this->getBestPracticeRepository()->find($bestPracticeId);

        if ($subscribe) {
            if ($this->subscribe($user, $bestPractice)) {
                $message = sprintf($this->translate("You have subscribed to %s: %s"), 
                        $bestPractice->getTitle(), $bestPractice->getSubtitle());
                $namespace = "success";
            } else {
                $message = sprintf($this->translate("You have already subscribed to %s: %s"),
                        $bestPractice->getTitle(), $bestPractice->getSubtitle());
                $namespace = "error";
            }
        } else {
            if ($this->unsubscribe($user, $bestPractice)) {
                $message = sprintf($this->translate("You have unsubscribed to %s: %s"), 
                        $bestPractice->getTitle(), $bestPractice->getSubtitle());
                $namespace = "success";
            } else {
                $message = sprintf($this->translate("Your subscription to %s: %s does not exist"),
                        $bestPractice->getTitle(), $bestPractice->getSubtitle());
                $namespace = "error";
            }
        }

        return new ServiceMessage($namespace, $message);
    }
    
    /**
     * 
     * @param int $userId
     * @param int $bestPracticeId
     * @return boolean
     */
    public function subscribe($user, $bestPractice) {
        $subscription = $this->getSubscription($user, $bestPractice);
        if (!$subscription) {
            $subscription = new Subscription();
            $subscription->setIdentifier($bestPractice->getIdentifier());
            $subscription->setUser($user);

            $this->persist($subscription);
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param int $userId
     * @param int $bestPracticeId
     * @return boolean
     */
    public function unsubscribe($user, $bestPractice) {
        $subscription = $this->getSubscription($user, $bestPractice);
        if ($subscription) {
            parent::remove($subscription);
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param string $identifier
     * @param integer $pendingNotificationId
     */
    public function updateSubscribersPendingNotification($identifier, $pendingNotificationId) {
        $this->getEntityRepository()
                ->updatePendingNotificacionByIdentifier($identifier, $pendingNotificationId);
    }
    
    /**
     * 
     * @param \Application\Entity\User $user
     * @param \BestPractice\Entity\BestPractice $bestPractice
     * @throws EntityDoesNotExistException
     * @return \BestPractice\Entity\Subscription
     */
    public function getSubscription($user, $bestPractice){
        if ($bestPractice) {
            if($user){
                $subscription = $this->getEntityRepository()->findBy(
                        array("user" => $user->getUserId(),
                            "identifier" => $bestPractice->getIdentifier()));
                
                return $subscription ? $subscription[0] : $subscription;
            }
            $this->displayEntityNotExistException($this->translate('User does not exist.'));
        }
        $this->displayEntityNotExistException($this->translate('Best Practice does not exist.'));
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
