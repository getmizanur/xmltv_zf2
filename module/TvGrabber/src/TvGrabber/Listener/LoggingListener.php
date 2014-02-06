<?php

namespace TvGrabber\Listener;

use Zend\Db\TableGateway\Feature\EventFeature\TableGatewayEvent;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Mvc\MvcEvent;

class LoggingListener implements ListenerAggregateInterface
{
    protected $logger = null;
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'preInitializer', array($this, 'preInitializer'), 100
        );
        $this->listeners[] = $events->attach(
            'postInsert', array($this, 'postInsert'), 200
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

    public function preInitializer(EventInterface $e)
    {
        $params = $e->getParams();

        $logger = new Logger();
        $logger->addWriter(new Stream(__DIR__ . '/../../../../../data/log/' . $params['companyNamespace'] . ".log"));

        switch($params['logger_const']) {
            case 'INFO':
                $logger->log(Logger::INFO, 
                    'Started processing ' . $params['companyNamespace']
                    . ' EPG data'
                );
                break;
            case 'ERR':
            default:
                $logger->log(Logger::ERR, 
                    'Failed to start processing ' . $params['epgService']);
        }
    }

    public function postInsert(EventInterface $e)
    {
        $params = $e->getParams();

        $logger = new Logger();
        $logger->addWriter(new Stream(__DIR__ . '/../../../../../data/log/' . $params['companyNamespace'] . ".log"));

        switch($params['logger_const']) {
            case 'INFO':
                $logger->log(Logger::INFO, 
                    '"' . $params['epgTitle'] . ' ' . $params['epgStart'] . '" for ' 
                    . $params['epgChannel'] . ' was inserted'
                );
                break;
            case 'ERR':
            default:
                $logger->log(Logger::ERR, 
                    '"' . $params['epgTitle'] . ' ' . $params['epgStart'] . '" for ' 
                    . $params['epgChannel'] . ' was not inserted'
                );
        }
    }
}
