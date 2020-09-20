<?php

namespace Application\Service;

use Sysco\Aurora\Service\Service;

class ConfigService extends Service
{

    protected $config;

    public function get($key)
    {
        return $this->config[$key];
    }

}