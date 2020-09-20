<?php
namespace Application\Validator;

use Zend\Validator\Exception;
use Application\Service\UserService;

/**
 * Class that validates if user exists and if its state is active
 *
 */
class ObjectExistsActive extends \DoctrineModule\Validator\ObjectExists
{

    /**
     * {@inheritDoc}
     */
    public function isValid($value)
    {
        $value = $this->cleanSearchValue($value);        
        $additionalCriteria = array('state' => UserService::USER_STATE_ACTIVE);
        $criteria = array_merge($value, $additionalCriteria);

        $match = $this->objectRepository->findOneBy($criteria);
    
        if (is_object($match)) {
            return true;
        }
    
        $this->error(self::ERROR_NO_OBJECT_FOUND, $value);
    
        return false;
    }
}