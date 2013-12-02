<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class PortListener extends AbstractListener
{
    public function setConfig($config)
    {   
    	if(!is_array($config)) {
            $config = array($config);
        }
    	return parent::setConfig($config);
    }
    
    public function authorizeModule($moduleName)
    {
        return in_array(@$_SERVER['SERVER_PORT'], $this->config);
    }
}
