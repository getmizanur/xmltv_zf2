<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class Logger extends AbstractListener
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
        if($this->config['enable']) {
        } 

        return true;
    }
}
