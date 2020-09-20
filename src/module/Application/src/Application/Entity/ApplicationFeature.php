<?php

namespace Application\Entity;

use Sysco\Aurora\Doctrine\ORM\Entity;

class ApplicationFeature extends Entity
{

    /**
     *  The name of the application.
     * @var string 
     */
    protected $name;
    protected $slug;
    protected $route;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

        
    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

}