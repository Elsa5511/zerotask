<?php
namespace Application\Service;

use Application\Entity\Role;

class RoleService extends AbstractBaseService
{
    
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Role');
    }

    public function getAllButGuest() {
        return $this->getEntityRepository()->getAllButGuest();
    }

    public function getNewRole()
    {
        return new Role();
    }

    public function getDefaultRole()
    {
        $roles = $this->getEntityRepository()->findBy(array('isDefault' => true));
        return empty($roles) ? null : current($roles);
    }

    /**
     * 
     * @param \Application\Entity\Role $role
     * @return array Flash message
     */
    public function deleteById(Role $role)
    {
        $namespace = "error";
        if ($role->getIsDefault()) {
            $message = $this->translate("Cannot delete the default role.");
        } else {
            $entitiesRelated = $this->getEntitiesRelated($role->getId());
            $isRelated = count($entitiesRelated) > 0;

            if ($isRelated) {
                $message = $this->getRelationshipErrorMessage($role->getName(), $entitiesRelated);
            } else {
                $namespace = "success";
                $message = $this->deleteRole($role);
            }
            $message = sprintf($message, $role->getName());
        }

        return array(
            "namespace" => $namespace,
            "message" => $message
        );
    }
    
    public function deleteRole(Role $role)
    {
        $this->setDefaultRoleForUsersWithRole($role);
        $this->remove($role);
        return $this->translate("Role \"%s\" was deleted successfully.");
    }

    public function setDefaultRoleForUsersWithRole(Role $role)
    {
        $usersWithSelectedRole = $this->getEntityManager()->getRepository('Application\Entity\User')->getUsersByRole($role->getRoleId());

        if (!empty($usersWithSelectedRole)) {
            $defaultRole = $this->getDefaultRole();

            foreach ($usersWithSelectedRole as $user) {
                $user->removeRole($role);
                if (!$user->hasRole($role->getRoleId())) {
                    $user->addRole($defaultRole);
                }

                $this->getEntityManager()->persist($user);
            }
        }
    }

    public function setUserRole($user, $roleId)
    {
        $roleEntity = $this->getEntityRepository()->find($roleId);
        $user->addRole($roleEntity);
        return $user;
    }

    public function setNewRoleForUser($user, $oldRoleId, $newRoleId)
    {
        $repository = $this->getEntityRepository();
        $oldRoleEntity = $repository->find($oldRoleId);
        $newRoleEntity = $repository->find($newRoleId);
        $user->removeRole($oldRoleEntity);
        $user->addRole($newRoleEntity);
        return $user;
    }

    public function isRoleUnique($roleId)
    {
        $validator = new \DoctrineModule\Validator\UniqueObject(array(
            'object_manager' => $this->getEntityManager(),
            'object_repository' => $this->getEntityRepository(),
            'fields' => array('roleId'),
            'use_context' => true
        ));
        $isUnique = $validator->isValid($roleId, array('roleId' => 'identifier'));
        return $isUnique;
    }

}