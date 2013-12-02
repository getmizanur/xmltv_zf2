<?php

namespace Mm\ModuleManager\Listener\Environment;

use Mm\ModuleManager\Listener\AbstractListener;
use Mm\ModuleManager\Listener\EnvironmentHandler;

class SapiListener extends AbstractListener
{
    public function authorizeModule($moduleName)
    {
        return php_sapi_name() === $this->config;
    }
}
