<?php

namespace Acl\Entity;

interface AclEntity {

    /**
     * Return the application in the entity
     * @return string
     */
    public function getApplication();

    /**
     * Set the application in the Entity
     * @param string $application
     * @return \Acl\Entity\AbstractEntity
     */
    public function setApplication($application);
}
