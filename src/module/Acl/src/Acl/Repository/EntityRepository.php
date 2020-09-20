<?php

namespace Acl\Repository;

use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use Doctrine\DBAL\LockMode;

class EntityRepository extends DoctrineEntityRepository
{

    /**
     * The key name of the current application
     * @var string 
     */
    protected $application = null;

    /**
     * Set the application for being used in the repository
     * @param string $application
     * @return \Acl\Repository\EntityRepository
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * Get the application name that is being used in the repository.
     * 
     * @return string The name of the repository.
     */
    public function getApplication() {
       return $this->application; 
    }
    
    
    /**
     * Check if the entity of this repository supports te application field.
     * @return boolean
     */
    private function entitySupportsApplication()
    {
        return $this->application !== null && $this->_class->hasField('application');
    }

    public function findAll()
    {
        $criteria = array();

        if ($this->entitySupportsApplication()) {
            $criteria['application'] = $this->application;
        }

        return $this->findBy($criteria);
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria = array(), array $orderBy = null, $limit = null, $offset = null)
    {
        if ($this->entitySupportsApplication()) {
            $criteria['application'] = $this->application;
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
    
    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed    $id          The identifier.
     * @param int      $lockMode    The lock mode.
     * @param int|null $lockVersion The lock version.
     *
     * @return object|null The entity instance or NULL if the entity can not be found
     *                     or if the application of the entity does not match the
     *                     current application.
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        $result = parent::find($id, $lockMode, $lockVersion);
        if ($result !== null && $this->entitySupportsApplication() && $this->hasApplicationMatch($result)) {
            return null;
        }
        else {
            return $result;
        }
    }        
    
    private function hasApplicationMatch($entity) {
        return $this->entitySupportsApplication() && $entity->getApplication() !== $this->application;
    }
    

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria = array(), array $orderBy = null)
    {
        if ($this->entitySupportsApplication()) {
            $criteria['application'] = $this->application;
        }

        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * Adds support for magic finders.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return array|object The found entity/entities.
     *
     * @throws ORMException
     * @throws \BadMethodCallException If the method called is an invalid find* method
     *                                 or no find* method at all and therefore an invalid
     *                                 method call.
     */
    public function __call($method, $arguments)
    {
        if (empty($arguments)) {
            throw ORMException::findByRequiresParameter($method);
        }

        return parent::__call($method, $arguments);
    }

}
