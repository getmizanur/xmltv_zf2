<?php
namespace Zend\Test\PHPUnit\Mvc\View;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;

class CaptureResponseListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'captureResponse'), -100);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function captureResponse(MvcEvent $e)
    {
        $e->stopPropagation(true);
    }
}
