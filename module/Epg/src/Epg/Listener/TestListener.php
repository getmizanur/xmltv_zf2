<?php

namespace Epg\Listener;

use Zend\Db\TableGateway\Feature\EventFeature\TableGatewayEvent;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\MvcEvent;

class TestListener implements ListenerAggregateInterface
{
    protected $logger = null;
    protected $listeners = array();

    public function __construct($file)
    {
        $this->logger = new Logger();
        $this->logger->addWriter(new Stream($file));
    }

    public function attach(EventManagerInterface $events)
    {
        $event = "test";
        $test = "value";
        $params = compact($event, $test);
        $this->listeners[] = $events->attach(
            'preTest', array($this, 'doTest1'), 100
        );
        $this->listeners[] = $events->attach(
            'postTest', array($this, 'doTest2'), 200
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

    public function doTest1(EventInterface $e)
    {
        $this->logger->log(
            Logger::INFO,
            'test1'
        ); 
    }

    public function doTest2(EventInterface $e)
    {
        $this->logger->log(
            Logger::INFO,
            'test2'
        ); 
    }
}
