<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class HttpsListener extends AbstractListener
{
    public function authorizeModule($moduleName)
    {
        return @$_SERVER['HTTPS'] == $this->config;
    }
}
