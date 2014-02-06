<?php

namespace Epg\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\Model\ViewModel;
use Zend\Log\Logger;
use Zend\Mvc\MvcEvent;

class ErrorListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logErrors'),
            -100
        );
    }

    public function detach(EventManagerInterface $events)
    {
        foreach($this->listeners  as $index => $listener) {
            if($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function logErrors(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $logger = $sm->get('Epg\Logging\Service');

        $obj = $e->getResult();
        $message = null;

        if($obj instanceof ViewModel) {
            $message = $obj->message;
        }else{
            $exception = $obj->exception;
            $message = $exception->getCode() . ": ";
            $message .= $exception->getMessage() . "\n";
            $message .= str_pad('-', 120, '-') . "\n";
            $message .= $exception->getFile() . " (";
            $message .= $exception->getLine() . ")\n";
            $message .= str_pad('-', 120, '-') . "\n";
            $message .= $exception->getTraceAsString() . "\n";
            $message .= str_pad('-', 120, '-') . "\n";
        }
        $logger->log(Logger::ERR, $message);
    }
}
