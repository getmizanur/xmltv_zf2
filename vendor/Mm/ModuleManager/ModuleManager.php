<?php
namespace Mm\ModuleManager;

use Zend\ModuleManager\ModuleManager as BaseModuleManager;

class ModuleManager extends BaseModuleManager
{
    public function loadModules()
    {
        $this->getEventManager()->trigger(
            ModuleEvent::EVENT_LOAD_MODULES_AUTH, 
            $this, 
            $this->getEvent()
        );
        return parent::loadModules();
    }

    public function onLoadModulesAuth()
    {
        if (true === $this->modulesAreLoaded) {
            return $this;
        }

        $modules = array();
    	foreach ($this->getModules() as $moduleName) {
            $auth = $this->loadModuleAuth($moduleName);
            if($auth) {
                $modules[] = $moduleName;
            }
        }
        $this->setModules($modules);
    }

    public function loadModuleAuth($moduleName)
    {
        $event = $this->getEvent();
        $event->setModuleName($moduleName);
        
        $result = $this->getEventManager()->trigger(
            ModuleEvent::EVENT_LOAD_MODULE_AUTH, 
            $this, 
            $event, 
            function($r) {
                return !$r;
            }
        );
        
        if(!$result->last()) {
            return false;
        }
        
        return true;
    }

    protected function attachDefaultListeners()
    {
        $events = $this->getEventManager();
        $events->attach(
            ModuleEvent::EVENT_LOAD_MODULES_AUTH, 
            array(
                $this, 
                'onLoadModulesAuth'
            )
        );
        parent::attachDefaultListeners();
    }

    public function getEvent()
    {
        if (!$this->event instanceof ModuleEvent) {
            $this->setEvent(new ModuleEvent);
        }
        return $this->event;
    }
}
