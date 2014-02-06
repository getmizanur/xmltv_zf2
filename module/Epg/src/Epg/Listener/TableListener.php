<?php

namespace Epg\Listener;

use Zend\Db\TableGateway\Feature\EventFeature\TableGatewayEvent;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\MvcEvent;

class TableListener implements ListenerAggregateInterface
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
        $this->listeners[] = $events->attach(
            'postInitialize', array($this, 'logPostInitialize')
        );
        $this->listeners[] = $events->attach(
            'postInsert', array($this, 'logPostInsert')
        );
        $this->listeners[] = $events->attach(
            'postDelete', array($this, 'logPostDelete')
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

    public function logPostInitialize(TableGatewayEvent $e)
    {
        $this->logger->log(
            Logger::INFO,
            'TableGateway for table "' . $e->getTarget()->getTable() 
                . '" initialized'
        ); 
    }

    public function logPostInsert(TableGatewayEvent $e)
    {
        $driver = $e->getTarget()->getAdapter()->getDriver();
        $params = $e->getParam('statement')->getParameterContainer();
        $id = $driver->getLastGeneratedValue();

        $this->logger->log(
            Logger::INFO,
            'Values with ID "' . $id
                . '" in table "' . $e->getTarget()->getTable() 
                . '" inserted'
        ); 
    }

    public function logPostDelete(TableGatewayEvent $e)
    {
        $params = $e->getParam('statement')->getParameterContainer();

        $this->logger->log(
            Logger::INFO,
            'Dataset with ID "' . $params->offsetGet('where1')
                . '" from table "' . $e->getTarget()->getTable()
                . '" deleted'
        ); 
    }
}
