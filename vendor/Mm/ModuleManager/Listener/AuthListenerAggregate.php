<?php
namespace Mm\ModuleManager\Listener;

use Mm\ModuleManager\ModuleEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\EventManager\EventCollection;

use Mm\ModuleManager\Listener\ListenerManager;

class AuthListenerAggregate extends DefaultListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {	
        $options = $this->getOptions();
        $lazyLoading = $options->getLazyLoading();

        $listenerManager = new AuthManager($lazyLoading);
        $this->listeners[] = $events->attach(
            ModuleEvent::EVENT_LOAD_MODULE_AUTH, 
            array($listenerManager, 'authorize')
        );
        
        return parent::attach($events);
    }
}
