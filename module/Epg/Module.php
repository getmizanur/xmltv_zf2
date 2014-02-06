<?php

namespace Epg;

use Epg\Model\Table;
use Epg\Model\Service\EpgService;

use Epg\Listener\ErrorListener;
use Epg\Listener\RunTimeListener;
use Epg\Listener\TableListener;
use Epg\Listener\TestListener;

use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface as ModuleManager;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\TableGateway\Feature;
use Zend\Db\TableGateway\Feature\EventFeature;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

use Zend\EventManager\StaticEventManager;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManager;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;

use Epg\Authentication\Storage\AuthStorage;

class Module implements 
    BootstrapListenerInterface, 
    AutoloaderProviderInterface, 
    ServiceProviderInterface,
    ConfigProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attachAggregate(new ErrorListener());
        $eventManager->attachAggregate(new RunTimeListener());
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                //'Epg\Model\Service\EpgService' => 'Epg\Model\Service\EpgService',
            ),
            'factories' => array(
                'ClietsDbAdapter' => function($sm) {
                    $config = $sm->get('config');
                    $config = $config['db_adapter_manager']['synapse']['simplestreamClients'];
                    $dbAdapter = new DbAdapter($config);
                    return $dbAdapter;
                },
                'CatchupDbAdapter' => function($sm) {
                    $config = $sm->get('config');
                    $config = $config['db_adapter_manager']['synapse']['catchuptv'];
                    $dbAdapter = new DbAdapter($config);
                    return $dbAdapter;
                },
                'CustomersDbAdapter' => function($sm) {
                    $config = $sm->get('config');
                    $config = $config['db_adapter_manager']['synapse']['catchuptv'];
                    $dbAdapter = new DbAdapter($config);
                    return $dbAdapter;
                },
                'Epg\Model\Table\EpgTable' => function($sm) {
                    $file = __DIR__ . '/../../data/log/epg_tables.log';
                    $eventFeature = new EventFeature();
                    $eventFeature->getEventManager()->attach(new TableListener($file));

                    return new Table\EpgTable(
                        'epg', $sm->get('CatchupDbAdapter'), 
                        $eventFeature
                    );
                },
                'Epg\Logging\Service' => function($sm) {
                    $logFile = __DIR__ . '/../../data/log/epg_error.log'; 

                    $writer = new Stream($logFile);
                    $logger = new Logger();
                    $logger->addWriter($writer);

                    return $logger;
                },
                "Epg\Model\Service\EpgService" => function($sm) {
                    $file = __DIR__ . '/../../data/log/epg_test.log';

                    $eventManager = new EventManager();
                    $eventManager->attachAggregate(new TestListener($file));
                    
                    $epgService = new EpgService();
                    $epgService->setEventManager($eventManager);

                    return $epgService;
                }

            ),
            'aliases' => array(
                'Epg\Model\Table\EpgModel' => 'Epg\Model\Table\EpgTable',
            ),
        );
    }
}
