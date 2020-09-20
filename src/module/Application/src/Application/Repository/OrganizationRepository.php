<?php

namespace Application\Repository;

use Acl\Repository\EntityRepository;

class OrganizationRepository extends EntityRepository
{
    
    /**
     * 
     * @param int $organizationId
     * @param type $translator
     * @return type $result
     */
    public function getEntitiesRelated($organizationId, $translator)
    {        
        $users = $this->getUsers($organizationId);
        if(count($users) > 0) {
            $resultKey = $translator->translate("Users");
            $users = array($resultKey => $users);
        }

        $equipments = $this->getEquipments($organizationId);
        if(count($equipments) > 0) {
            $resultKey = $translator->translate("Equipments");
            $equipments = array($resultKey => $equipments);
        }
        
        $equipmentInstances = $this->getEquipmentInstances($organizationId);
        if(count($equipmentInstances) > 0) {
            $resultKey = $translator->translate("Equipment instances");
            $equipmentInstances = array($resultKey => $equipmentInstances);
        }
        
        $checkouts = $this->getCheckouts($organizationId);
        if(count($checkouts) > 0) {
            $resultKey = $translator->translate("Checkouts for equipment instances");
            $checkouts = array($resultKey => $checkouts);
        }
        
        $periodicControls = $this->getPeriodicControls($organizationId);
        if(count($periodicControls) > 0) {
            $resultKey = $translator->translate("Periodic controls for equipment instances");
            $periodicControls = array($resultKey => $periodicControls);
        }

        $result = array_merge($users, $equipments, $equipmentInstances, $checkouts, $periodicControls);
        return $result;
    }

    public function nameIsUnique($name, $currentEntityId) {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('COUNT(o)')
            ->from($this->getEntityName(), 'o')
            ->andWhere($queryBuilder->expr()->eq('o.name', ':name'))
            ->setParameter('name', $name);
        if ($currentEntityId) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq('o.organizationId', ':id'))
                ->setParameter('id', $currentEntityId);
            ;
        }

        $query = $queryBuilder->getQuery();
        $result = (int)$query->getSingleScalarResult();
        return $result === 0;
    }

    /**
     * 
     * @param int $equipmentTaxonomyId
     * @return array
     */
    private function getUsers($organizationId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Application\Entity\User');
        $users = $repository->findBy(array('organizationId' => $organizationId));
        return $users;
    }

    private function getEquipments($organizationId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Equipment\Entity\Equipment');
        $equipmentsByVendor = $repository->findBy(array('vendor' => $organizationId));
        $equipmentsByManufacturer = $repository->findBy(array('manufacturer' => $organizationId));
        
        return array_merge($equipmentsByManufacturer, $equipmentsByVendor);
    }

    private function getEquipmentInstances($organizationId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Equipment\Entity\EquipmentInstance');
        $instances = $repository->findBy(array('owner' => $organizationId));
        return $instances;
    }
    
    private function getCheckouts($organizationId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Equipment\Entity\Checkout');
        $checkouts = $repository->findBy(array('organization' => $organizationId));
        return $checkouts;
    }

    private function getPeriodicControls($organizationId)
    {
        $repository = $this->getEntityManager()
                ->getRepository('Equipment\Entity\PeriodicControl');
        $periodicControls = $repository->findBy(array('expertiseOrgan' => $organizationId));
        return $periodicControls;
    }

    public function findControlOrgans() {
        return $this->findBy(array(
            'type' => 'control_organ',
            'status' => 'active'
        ));
    }
}
