<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class RemoteAddrListener extends AbstractListener
{
    public function setConfig($config)
    {   
    	if(is_string($config)) {
            $config = array($config);
        }
    	return parent::setConfig($config);
    }
	
    public function authorizeModule($moduleName)
    {
        return in_array(@$_SERVER['REMOTE_ADDR'], $this->config);
    }
}
