<?php

namespace BestPractice\Entity;

use Doctrine\ORM\Mapping as ORM;
use BestPractice\Entity\BestPractice;
use Application\Entity\User;

/**
 * @ORM\Table(name="subscription")
 * @ORM\Entity(repositoryClass="BestPractice\Repository\SubscriptionRepository")
 */
class Subscription {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="subscription_id", type="integer")
     * */
    protected $subscriptionId;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=50, nullable=false)
     */
    protected $identifier;

    /**
     * @var \Application\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id", nullable=false)
     * })
     */
    protected $user;
    
    /**
     * @var integer  bestPracticeId of the Revision to notify to the subscriber (default:0 => not notify)
     * 
     * @ORM\Column(name="pending_notification_id", type="integer", nullable=false)
     */
    protected $pendingNotificationId = 0;

        
    
    public function getSubscriptionId() {
        return $this->subscriptionId;
    }

    public function setSubscriptionId($subscriptionId) {
        $this->subscriptionId = $subscriptionId;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }
    
    public function getPendingNotificationId() {
        return $this->pendingNotificationId;
    }

    public function setPendingNotificationId($pendingNotificationId) {
        $this->pendingNotificationId = $pendingNotificationId;
    }




}
