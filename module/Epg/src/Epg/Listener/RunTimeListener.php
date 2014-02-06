<?php

namespace Epg\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class RunTimeListener implements ListenerAggregateInterface
{
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_FINISH, array($this, 'displayRuntime')
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

    public function displayRunTime(MvcEvent $e)
    {
        $response = $e->getApplication()->getResponse();
        $body = $response->getBody();
        $startTime = ZF2_TIME_START;
        $endTime = microtime(true);
        $diffTime = $endTime - $startTime;

        $runtime = '<div style="border:1px solid #eee;"';
        $runtime .= 'background-color: #f8f8f8; text-align: center; ';
        $runtime .= 'color: #888; margin: 10px; padding: 10px;">';
        $runtime .= 'Runtime: ' . round($diffTime, 5) . ' second';
        $runtime .= '</div>';

        $body = str_replace('</body>', $runtime . '</body>', $body); 

        $response->setContent($body);
    }
}
