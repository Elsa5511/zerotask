<?php

namespace Application\Service;

use Sysco\Aurora\Doctrine\ORM\Service;
use Zend\Crypt\Password\Bcrypt;
use Sysco\Aurora\Stdlib\DateTime;

class UserService extends Service
{
    //these constants also are used in UserController and User entity
    const USER_STATE_ACTIVE = 1;
    const USER_STATE_DELETED = 9;

    private function getEntityRepository() {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

    /**
     * @return \Application\Entity\User[] a list of all active and inactive users
     */
    public function fetchAll()
    {
        return $this->getEntityRepository()->findAll();
    }
    
    /**
     * Change the state of the user
     * @param integer $id user id
     * @param integer $state user state
     * @return integer
     */
    public function changeState($id, $state, $passwordExpirationEnabled = false){
        $user = $this->getUser($id);
        if($user) {
            $user->setState($state);
            if($state == self::USER_STATE_ACTIVE && $passwordExpirationEnabled) {
                $datePasswordUpdated = $user->getDatePasswordUpdated();
                if ($datePasswordUpdated && $datePasswordUpdated instanceof \DateTime) {
                    $now = new DateTime("now");
                    $user->setDatePasswordUpdated($now);
                }
            }
            parent::persist($user);

            return 1;
        }

        return 0;
    }

    /**
     * Delete many user, updating its state value to '9'
     * (9 = deleted)
     */
    public function deleteMany($ids)
    {
        $dql = "UPDATE Application\Entity\User u SET u.state = ?1 WHERE u.userId IN (:list)";
        $userIDs = array_map('intval', explode(',', $ids));
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter(1, self::USER_STATE_DELETED)->setParameter('list', $userIDs);
        return $query->getResult();
    }

    /**
     * @param $userId
     * @return \Application\Entity\User
     */
    public function getUser($userId)
    {
        return $this->getEntityRepository()->find($userId);
    }

    /**
     * Setting additional user values and 
     * persisting the entity to database.
     *
     */
    public function persistFormData($user, $currentUser, $formData = array())
    {
        $now = new DateTime('NOW');
        $displayName = $user->getFirstName() . ' ' . $user->getLastName();
        $password = $user->getPassword();
        $isEditAction = $user->getId() > 0;



        if ($isEditAction) {
            if (!isset($formData['competenceAreas'])) {
                $user->getCompetenceAreas()->clear();
            }
            $newPassword = array_key_exists('password', $formData) ? $formData['password'] : "";
            if (strlen($newPassword) > 5) {
                $password = $this->getEncryptedPassword($newPassword);
                $user->setDatePasswordUpdated($now);
            }

            $userId = is_array($currentUser) ? $currentUser["userId"] : $currentUser->getUserId();

            if ($user->getUserId() == $userId)
            {
                $formerSelf = $this->getUser($user->getUserId());
                $user->setRoles($formerSelf->getRoles());
            }

        } else { /* Add action */
            $user->setDateAdd($now);
            $user->setDatePasswordUpdated($now);
            $password = $this->getEncryptedPassword($password);
        }

        $user->setPassword($password);
        $user->setDateUpdate($now);
        $user->setDisplayName($displayName);

        parent::persist($user);
        return $user->getId();
    }

    private function isUserUpdatedLessOneHour($user)
    {
        $isLessOneHour = false;
        if (isset($user)) {
            $dateUpdated = $user->getDateUpdate();
            $differenceTime = $dateUpdated->diff(new DateTime("now"));
            $isLessOneHour = ($differenceTime->days == 0) && ($differenceTime->h == 0);
        }
        return $isLessOneHour;
    }

    /**
     * Reset password and sent it by email
     * 
     * @param string $securityKey
     * @return boolean
     */
    public function resetPassword($securityKey)
    {
        $entity = $this->getEntityRepository()->findOneBy(
                array(
                    'securityKey' => $securityKey
        ));

        if ($this->isUserUpdatedLessOneHour($entity)) {
            $newPassword = $this->getRandomString($length = 8);
            $encryptedPassword = $this->getEncryptedPassword($newPassword);
            $entity->setPassword($encryptedPassword);
            $entity->setSecurityKey(null);
            $now = new DateTime("now");
            $entity->setDateUpdate($now);
            $entity->setDatePasswordUpdated($now);
            $this->persist($entity);

            $template = array(
                'source' => 'tpl/email/reset-password',
                'params' => array(
                    'newPassword' => $newPassword
                ),
            );

            $subject = "Passord ble nullstilt";

            $this->sendEmail($entity->getEmail(), $subject, $template);

            return true;
        } else {
            return false;
        }
    }

    /**
     * checks if user exists and if it's active
     *  
     * @param entity $user
     * @return boolean
     */
    public function sendForgotPasswordEmail($user)
    {

        $entity = $this->assignSecurityKey($user);

        $template = array(
            'source' => 'tpl/email/forgot-password',
            'params' => array(
                'securityKey' => $entity->getSecurityKey()
            ),
        );

        $subject = "Nullstill passord";

        $this->sendEmail($entity->getEmail(), $subject, $template);
    }

    /**
     * @param int $expiresIn number of days when the password will expire
     * @param int $warnBefore number of days to notify the user before password expiration
     */
    public function checkPasswordExpiration($hostname = '', $expiresIn = 30, $warnBefore = 14) {
        $users = $this->fetchAll();

        foreach($users as $user) {
            //Only for active users
            if($user->getState() == UserService::USER_STATE_ACTIVE) {
                $datePasswordUpdated = $user->getDatePasswordUpdated();

                if($datePasswordUpdated && $datePasswordUpdated instanceof \DateTime) {
                    $today = new \DateTime();
                    $today->setTime(0, 0, 0);
                    $datePasswordUpdated->setTime(0, 0, 0);

                    $diff = $today->diff($datePasswordUpdated);
                    if($diff && $diff->y == 0 && $diff->m == $expiresIn && $diff->d == 1) {
                        echo "user has been deactivated because password has expired";
                        //Changing password without sending email (disabling password temporary)
                        $newPassword = $this->getRandomString($length = 8);
                        $encryptedPassword = $this->getEncryptedPassword($newPassword);
                        $user->setPassword($encryptedPassword);
                        $user->setSecurityKey(null);
                        $now = new DateTime("now");
                        $user->setDateUpdate($now);
                        $user->setDatePasswordUpdated($now);
                        $user->setState(UserService::USER_STATE_DELETED);

                        $this->persist($user);

                        $template = array(
                            'source' => 'tpl/email/password-expired',
                            'params' => array(),
                            'map' => $this->templateMap
                        );

                        $subject = "Vidum - System | " . $this->translate("Account deactivated");

                        $this->sendEmail($user->getEmail(), $subject, $template);
                    }

                    $dateExpiration = new \DateTime($datePasswordUpdated);
                    $dateExpiration->add(new \DateInterval('P3M'));
                    if($dateExpiration > $today)
                    {
                        $diff2 = $dateExpiration->diff($today);
                        if($diff2 && $diff2->days == $warnBefore) {
                            echo "Password will expire in $warnBefore days";
                            $template = array(
                                'source' => 'tpl/email/password-about-expire',
                                'params' => array(
                                    'hostname' => $hostname,
                                    'warnBefore' => $warnBefore
                                ),
                                'map' => $this->templateMap
                            );

                            $subject = "Vidum - System | " . $this->translate("Password expiration");

                            $this->sendEmail($user->getEmail(), $subject, $template);
                        }
                    }
                }
            }
        }
    }
    
    private function sendEmail($email, $subject, $template = null, $text = '') {
        $this->getDependency('mail_service')->sendMessage($email, $subject, 
            $template, $text);
    }

    /**
     * Generate a security key and assigns it to user
     * 
     * @param entity $user
     * @return a user entity
     */
    private function assignSecurityKey($user)
    {
        $entity = $this->getEntityRepository()->findOneBy(
                array('username' => $user->getUsername())
        );
        $securityKey = sha1(time());
        $entity->setSecurityKey($securityKey);
        $entity->setDateUpdate(new DateTime('NOW'));
        return parent::persist($entity);
    }

    /**
     *
     * @param $password
     * @return string encrypted Password
     */
    private function getEncryptedPassword($password)
    {
        $zfcuserService = $this->getDependency('zfcuser_user_service');
        $cost = $zfcuserService->getOptions()->getPasswordCost();
        $bcrypt = new Bcrypt;
        $bcrypt->setCost($cost);
        return $bcrypt->create($password);
    }

    /**
     * Generate a random string 
     * (used for generate new passwords) 
     * 
     * @param $length length of string
     * @return string
     */
    private function getRandomString($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

}