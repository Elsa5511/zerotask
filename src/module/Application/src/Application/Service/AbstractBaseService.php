<?php
namespace Application\Service;

use Acl\Service\AbstractService as AclService;
use Application\Entity\StandardMessages;

/**
 * 
 *
 * @author cristhian
 */
abstract class AbstractBaseService extends AclService
{

    /**
     * 
     * @return type List of entities
     */
    public function findAll()
    {
        return $this->getEntityRepository()->findAll();
    }

    /**
     * 
     * @param type $entityId
     * @return Entity
     */
    public function findById($entityId)
    {
        return $this->getEntityRepository()->find($entityId);
    }
    
    /**
     * 
     * @param integer $equipmentId
     */
    public function findByEquipment($equipmentId)
    {
        return $this->getEntityRepository()->findBy(
                        array("equipment" => $equipmentId)
        );
    }
    
    /**
     * Check if exists related entities
     * 
     * @param type $entityId
     * @param type $translator
     * @return array Entity List
     */
    protected function getEntitiesRelated($entityId) {
        return $this->getEntityRepository()
                        ->getEntitiesRelated($entityId, $this->getTranslator());
    }
    
    protected function getTranslator()
    {
        return $this->getDependency('translator');
    }
    
    protected function getImageUtility()
    {
        return $this->getDependency("imageUtility");
    }

    /**
     * 
     * 
     * @param type $entityName
     * @param type $entitiesRelatedByGroup
     * @return string Message for relationships
     */
    protected function getRelationshipErrorMessage($entitiesRelatedByGroup)
    {
        $separator = " - ";
        $firstLineMessage = $this->translate('"%s" can\'t be deleted because it is related to other entities.');
        $secondLineMessage = $this->translate('Edit the relationships and try again:');
        $messageError = $firstLineMessage . " " . $secondLineMessage . " ";        
        
        foreach ($entitiesRelatedByGroup as $groupName => $entityGroup) {
            $messageError .= $separator . $groupName . " (" ;            
            $messageError .= implode(", ", $entityGroup);            
            $messageError .= ").";
        }
        return $messageError;
    }
    
    public function removeImage($imageName, $folderPath)
    {
        if ($imageName !== '' && $imageName !== null) {
            $source = $folderPath . $imageName;
            if (file_exists($source)) {
                unlink($source);
            }
        }
    }

    /**
     * 
     * @param type $errorMessage
     * @throws CannotDeleteException
     */
    public function displayEntityRelatedException($exceptionMessage) 
    {
        throw new CannotDeleteException($exceptionMessage);
    }
    
    /**
     * 
     * @param type $exceptionMessage
     * @throws EntityDoesNotExistException
     */
    public function displayEntityNotExistException($exceptionMessage)
    {
        throw new EntityDoesNotExistException($exceptionMessage);
    }

    /**
     * @return StandardMessages
     */
    protected function getStandardMessages() {
        return new StandardMessages($this->getTranslator());
    }

    
}