<?php

namespace Acl\Listener;

use BjyAuthorize\Service\Authorize;
use Doctrine\ORM\Event\LifecycleEventArgs;
use BjyAuthorize\Exception\UnAuthorizedException;

class Listener
{

    const FUNCTION_CREATE = 'create';
    const FUNCTION_UPDATE = 'update';
    const FUNCTION_READ = 'read';
    const FUNCTION_DELETE = 'delete';

    /**
     *
     * @var Authorize;
     */
    private $authorize;

    /**
     *
     * @var string 
     */
    private $application = null;

    /**
     * 
     * @param array $options
     * @param \BjyAuthorize\Service\Authorize $authorize
     */
    public function __construct(array $options, Authorize $authorize)
    {
        $this->setOptions($options);
        $this->authorize = $authorize;
    }

    /**
     * 
     * @param array $options
     * @return \Acl\Listener\Listener
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('application', $options)) {
            $this->application = ucfirst($options['application']);
        }

        return $this;
    }

    /**
     * Check if has access to action of a resource
     * @param string $resource
     * @param string $function
     * @return boolean
     */
    private function hasAccessToFunction($resource, $function)
    {
        if ($resource == 'Application\Entity\Role') {
            return true;
        }

        if (null === $this->application) {
            $isAllowedResult = $this->authorize->isAllowed($resource, $function);
        } else {
            $isAllowedResult = ($this->authorize->isAllowed($this->application . '\\' . $resource, $function) ||
                    $this->authorize->isAllowed($resource, $function));
        }

        return $isAllowedResult;
    }

    /**
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $this->checkAccessToEntityFunction($eventArgs, self::FUNCTION_CREATE);
    }

    /**
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $this->checkAccessToEntityFunction($eventArgs, self::FUNCTION_READ);
    }

    /**
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->checkAccessToEntityFunction($eventArgs, self::FUNCTION_UPDATE);
    }

    /**
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $this->checkAccessToEntityFunction($eventArgs, self::FUNCTION_DELETE);
    }

    /**
     * 
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @param string $function
     * @throws UnAuthorizedException
     */
    private function checkAccessToEntityFunction(LifecycleEventArgs $eventArgs, $function)
    {
        $entity = $eventArgs->getEntity();

        if (!is_object($this->authorize)) {
            throw new UnAuthorizedException('Not any authorize driver have been instanciated.');
        }

        $resource = $this->getResourceName($entity);

        if (!$this->hasAccessToFunction($resource, $function)) {
            throw new UnAuthorizedException(sprintf("You are not authorized to use the function '%s' of the resource '%s'", $function, $resource));
        }
    }

    /**
     * Return the resource name based on an entity
     * @param object $entity
     * @return string
     */
    private function getResourceName($entity)
    {
        $classname = get_class($entity);

        if (false === ($pos = strpos($classname, '\__CG__\\', 1))) {
            return $classname;
        } else {
            return substr($classname, $pos + 8);
        }
    }

}
