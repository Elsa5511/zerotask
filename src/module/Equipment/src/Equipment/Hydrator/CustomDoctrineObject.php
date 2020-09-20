<?php
namespace Equipment\Hydrator;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use \DateTime;

/**
 * This class overrides some methods from DoctrineModule\Stdlib\Hydrator
 * We are using it as hydrator object in fieldsets and forms 
 *
 */
class CustomDoctrineObject extends DoctrineObject
{
    /**
     * Handle various type conversions that should be supported natively by Doctrine (like DateTime)
     *
     * @param  mixed  $value
     * @param  string $typeOfField
     * @return DateTime
     */
    protected function handleTypeConversions($value, $typeOfField)
    {
        switch($typeOfField) {
        	case 'datetime':
        	case 'time':
        	case 'date':
        	    if (is_int($value)) {
        	        $dateTime = new DateTime();
        	        $dateTime->setTimestamp($value);
        	        $value = $dateTime;

        	    } elseif ('' == $value) {
        	        $value = null;    

        	    } elseif (is_string($value)) {        	    
        	        $value = new \DateTime($value);
        	    }    
        	    break;
        	default:
        }    
        return $value;
    }
}