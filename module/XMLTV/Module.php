<?php

namespace XMLTV;

use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter as DbAdapter;

class Module implements 
    AutoloaderProviderInterface, 
    ConfigProviderInterface,
    ConsoleUsageProviderInterface, 
    ServiceProviderInterface
{
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
    
    public function getConsoleUsage(AdapterInterface $console){   
        return array(          
            'Use --import-epg to import tvp epg from EBS',
        );
    }
    
    public function getServiceConfig()                                                                                       
    {
        return array(
            'invokables' => array(
                'EpgService' => 'XMLTV\Model\Service\EpgService'
            ),
            'factories' => array(               
                'XmltvOptions' => function($sm) {
                    $config = $sm->get('Config');   
                    return new \XMLTV\Options\XmltvOptions($config['xmltv_options']);
                },             
                'CatchuptvDbAdapter' => function($sm) {  
                    $config = $sm->get('config');   
                    $config = $config['db_adapter_manager']['xmltv']['catchuptv'];
                    $dbAdapter = new DbAdapter($config);
                    return $dbAdapter;              
                },             
                'SimpleStreamClientsDbAdapter' => function($sm) {  
                    $config = $sm->get('config');   
                    $config = $config['db_adapter_manager']['xmltv']['simplestreamClients'];
                    $dbAdapter = new DbAdapter($config);
                    return $dbAdapter;              
                }, 
                'EpgTable' => function($sm) { 
                    $epgTable = new Model\Table\EpgTable(
                        'epg', $sm->get('CatchuptvDbAdapter')
                    );
                    $epgTable->setServiceLocator($sm);
                    return $epgTable;
                },
                'LiveStreamsTable' => function($sm) { 
                    $liveStreamsTable = new Model\Table\LiveStreamsTable(
                        'live_streams', $sm->get('SimpleStreamClientsDbAdapter')
                    );
                    $liveStreamsTable->setServiceLocator($sm);
                    return $liveStreamsTable;
                },
            ),
            'aliases' => array(
                'XmltvOpt' => 'XmltvOptions',
                'EpgModel' => 'EpgTable', 
                'LiveStreamsModel' => 'LiveStreamsTable', 
            )
        );
    }   
}
