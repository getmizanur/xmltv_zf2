<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class TokenListener extends AbstractListener
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
            if(class_exists('Kooper\User\User')) {
                
            }
        } 

        return true;
    }
}
