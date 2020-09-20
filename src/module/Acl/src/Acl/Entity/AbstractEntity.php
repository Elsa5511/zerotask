<?php

namespace Acl\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sysco\Aurora\Doctrine\ORM\Entity;

/**
 * Description of BaseEntity
 *
 * @author José Carlos Chávez <jose.carlos.chavez@sysco.no>
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity extends Entity implements AclEntity
{

    /**
     * The application of the entity
     * @ORM\Column(type="string")
     */
    protected $application;

    /**
     * Return the application in the entity
     * @return string
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the application in the Entity
     * @param string $application
     * @return \Acl\Entity\AbstractEntity
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

}
