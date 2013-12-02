<?php
namespace Mm\ModuleManager\Listener;

use Zend\ModuleManager\Listener\ConfigListener as BaseConfigListener;
use Zend\ModuleManager\ModuleEvent;

class ConfigListener extends BaseConfigListener
{
    public function __construct(ListenerOptions $options = null)
    {
        parent::__construct($options);
        if ($this->hasCachedConfig()) {
            $this->skipConfig = true;
            $this->setMergedConfig($this->getCachedConfig());
        }
    }
    
    public function loadModulesPre(ModuleEvent $e)
    {
    	if($this->getOptions()->getConfigCacheEnabled()) {
            $this->getOptions()->setConfigCacheKey(
                implode('.',$e->getTarget()->getModules()).'.'. 
                $this->getOptions()->getConfigCacheKey()
            );
            if ($this->hasCachedConfig()) {
                $this->skipConfig = true;
                $this->setMergedConfig($this->getCachedConfig());
            }
    	}
        return parent::loadModulesPre($e);
    }
}
