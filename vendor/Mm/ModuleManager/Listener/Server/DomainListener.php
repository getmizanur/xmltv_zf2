<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class DomainListener extends AbstractListener
{
    public function authorizeModule($moduleName)
    {
    	$hostname = isset($_SERVER['SERVER_NAME']) ? 
            $_SERVER['SERVER_NAME'] : @$_SERVER['HTTP_HOST'];
        return $hostname === $this->config;
    }
}
