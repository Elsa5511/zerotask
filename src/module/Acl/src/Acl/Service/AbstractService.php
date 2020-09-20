<?php

namespace Acl\Service;

use Sysco\Aurora\Doctrine\ORM\Service as AuroraORMService;

/**
 * Description of Service
 *
 * @author José Carlos Chávez <jose.carlos.chavez@sysco.no>
 */
abstract class AbstractService extends AuroraORMService
{

    /**
     *
     * @var string
     */
    protected $application;

    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }

    public function persist(&$entity, $clear = false)
    {
        if ($entity instanceof \Acl\Entity\AclEntity && !$entity->hasApplication()) {
            $entity->setApplication($this->application);
        }

        return parent::persist($entity, $clear);
    }

    protected function getRepository($repositoryName)
    {
        $repository = $this->getEntityManager()->getRepository($repositoryName);
        $repository->setApplication($this->application);
        return $repository;
    }

}
