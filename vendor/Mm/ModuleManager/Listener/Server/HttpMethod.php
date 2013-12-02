<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class HttpMethod extends AbstractListener
{
    public function authorizeModule($moduleName)
    {
    	return strtolower($_SERVER['REQUEST_METHOD']) == strtolower($this->config);
    }
}
