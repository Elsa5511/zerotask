<?php

namespace Application\Service;

use Application\Repository\OrganizationRepository;
use Sysco\Aurora\Stdlib\DateTime;

class OrganizationService extends AbstractBaseService 
{
    protected $userRelationship = array();
    protected $equipmentRelationship = array();

    /**
     * @return OrganizationRepository
     */
    public function getEntityRepository() {
        return $this->getRepository('Application\Entity\Organization');
    }

    public function getFormOptions($criteria = array()) {

        $options = array();
        $entities = $this->fetchAll($criteria);
        foreach ($entities as $item) {
            $options[$item->getOrganizationId()] = $item->getName();
        }
        return $options;
    }

    public function getOrganization($organizationId) {
        return $this->getEntityRepository()->find($organizationId);
    }

    public function fetchAll() {
        return $this->getEntityRepository()->findAll();
    }

    public function persistData($entity) {
        $now = new DateTime('NOW');
        $isAddAction = is_null($entity->getOrganizationId());
        if ($isAddAction) {
            $entity->setDateAdd($now);
        }
        $entity->setDateUpdate($now);
        parent::persist($entity);
        return $entity->getOrganizationId();
    }

    public function nameIsUnique($name, $currentEntityId = null) {
        if ($name !== null) {
            return $this->getEntityRepository()->nameIsUnique($name, $currentEntityId);
        }
        else return true;
    }
}
