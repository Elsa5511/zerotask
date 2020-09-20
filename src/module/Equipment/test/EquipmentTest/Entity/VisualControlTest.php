<?php

namespace EquipmentTest\Entity;

use Application\Entity\Role;
use Application\Entity\User;
use Equipment\Entity\VisualControl;
use EquipmentTest\BaseSetUp;


class VisualControlTest extends BaseSetUp {
    private $currentUser;

    public function setUp() {
        $this->currentUser = new User();
        $this->currentUser->setId(999);
    }

    public function testIsDeletable_atCreation_isDeletable() {
        $visualControl = $this->setupVisualControl(new \DateTime());
        $this->assertTrue($this->isDeletable($visualControl));
    }

    public function testIsDeletable_afterTwentyThreeHours_isDeletable() {
        $now = new \DateTime();
        $twentyThreeHoursAgo = $now->sub(new \DateInterval("PT23H"));
        $visualControl = $this->setupVisualControl($twentyThreeHoursAgo);
        $this->assertTrue($this->isDeletable($visualControl));
    }

    public function testIsDeletable_afterTwentyFiveHours_isNotDeletable() {
        $now = new \DateTime();
        $twentyFiveHoursAgo = $now->sub(new \DateInterval("PT25H"));

        $visualControl = $this->setupVisualControl($twentyFiveHoursAgo);
        $this->assertFalse($this->isDeletable($visualControl));
    }

    private function setupVisualControl(\DateTime $createdTime) {
        $visualControl = new VisualControl();
        $visualControl->setRegisteredBy($this->currentUser);
        $visualControl->setCreatedTime($createdTime);
        return $visualControl;
    }

    public function testIsDeletable_withSameUser_isDeletable() {
        $visualControl = new VisualControl();
        $visualControl->setRegisteredBy($this->currentUser);
        $this->assertTrue($this->isDeletable($visualControl));
    }

    public function testIsDeletable_withDifferentUser_isNotDeletable() {
        $otherUser = new User();
        $otherUser->setId(888);
        $visualControl = new VisualControl();
        $visualControl->setRegisteredBy($otherUser);
        $this->assertFalse($this->isDeletable($visualControl));
    }

    public function testIsDeletable_withDifferentAdminUser_isDeletable() {
        $adminRole = new Role();
        $adminRole->setRoleId('admin');
        $this->currentUser->addRole($adminRole);

        $otherUser = new User();
        $otherUser->setId(888);

        $visualControl = new VisualControl();
        $visualControl->setRegisteredBy($otherUser);
        $this->assertTrue($this->isDeletable($visualControl));
    }

    private function isDeletable($visualControl) {
        return $visualControl->isDeletable($this->currentUser);
    }
}