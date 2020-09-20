<?php

namespace Application\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="application")
 */
class ApplicationDescription extends Entity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="application_id", type="integer")
     */
    protected $applicationId;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    public function getApplicationId() {
        return $this->applicationId;
    }

    public function getName() {
        return $this->name;
    }

    public function setApplicationId($applicationId) {
        $this->applicationId = $applicationId;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function __toString() {
        return strtoupper($this->getName());
    }
}
