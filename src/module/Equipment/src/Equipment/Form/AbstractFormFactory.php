<?php
namespace Equipment\Form;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Sysco\Aurora\Form\Form;

/**
 * Base class for each module form factory
 *
 * @author cristhian
 */
class AbstractFormFactory
{
    protected $translator;
    protected $objectManager;

    public function getTranslator()
    {
        return $this->translator;
    }

    public function setTranslator($value)
    {
        $this->translator = $value;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    public function setObjectManager($value)
    {
        $this->objectManager = $value;
    }
    
    /**
     * The fieldset will hydrate an object entity
     * 
     * @param Fieldset $fieldset
     * @param String $entityClassName
     */
    protected function setupFieldset($fieldset, $entityClassName){
        $hydrator = $this->getHydratorForm($entityClassName);
        $fieldset->setHydrator($hydrator);
        $fieldset->setTranslator($this->getTranslator());
        $fieldset->setUseAsBaseFieldset(true);
    }
    
    protected function getNewForm($name)
    {
        return new Form($name);
    }

    protected function getHydratorForm($entityName)
    {      
        return new DoctrineObject($this->getObjectManager(), 
                $entityName, false);
    }
}