<?php
namespace Acl\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\ORMException;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;

/**
 * This class is used for FormFactory because this class don't use AbstractService from ACL
 */
class AclEntityManager extends EntityManager 
{
    
    public function __construct(Connection $conn, Configuration $config, EventManager $eventManager)
    {
        parent::__construct($conn, $config, $eventManager);
    }
    
    public static function create($conn, Configuration $config, EventManager $eventManager = null) {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ? : new EventManager()));
        } else if ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        // This is where you return an instance of your custom class!
        return new AclEntityManager($conn, $config, $conn->getEventManager());
    }
    
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
     * Check if the repository supports te application field.
     * @return boolean
     */
    private function repositorySupportsApplication($repository)
    {
        if(method_exists($repository, 'getApplication') && $repository instanceof \Acl\Repository\EntityRepository){
            return true;
        }
        return false;
    }
    
    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     *
     * @return \Acl\Repository\EntityRepository The repository class.
     */
    public function getRepository($entityName)
    {
        $repository = parent::getRepository($entityName);
        if($this->repositorySupportsApplication($repository) && $this->application){
            $repository->setApplication($this->getApplication());
        }
        return $repository;
    }
}