<?php
namespace ApplicationTest\Entity;

use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase {
    
    /* Same with \Application\Service\UserService::USER_STATE_ACTIVE */
    const USER_STATE_ACTIVE = 1;

    public function testUserInitialState() {
        $user = new \Application\Entity\User();
        $this->assertNull($user->getUserId(), 
            '"User Id" should initially be null');
        $this->assertNull($user->getFirstname(), 
            '"First name" should initially be null');
        $this->assertNull($user->getLastname(), 
            '"Last name" should initially be null');
        $this->assertNull($user->getUsername(), 
            '"User name" should initially be null');
        $this->assertNull($user->getEmail(), '"Email" should initially be null');
        $this->assertEquals($user->getState(), self::USER_STATE_ACTIVE, 
            '"State" should initially be "1"');
        $this->assertEquals($user->_getRoles(), 
            new \Doctrine\Common\Collections\ArrayCollection(), 
            '"Roles" should initially be an empty Class');
    }
}