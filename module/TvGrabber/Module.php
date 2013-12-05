<?php

namespace TvGrabber;

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
            'Use --import-from-ebs - import epg from EBS',
            'Use --import-horse-and-country - import epg for "Horse and Country"',
            'Use --import-box-nation - import epg for "Box Nation"',
        );
    }
    
    public function getServiceConfig()                                                                                       
    {
        return array(
            'invokables' => array(
                'XmltvService' => 'TvGrabber\Model\Service\XmltvService',
                'TvAnytimeService' => 'TvGrabber\Model\Service\TvAnytimeService',
                'ZeusService' => 'TvGrabber\Model\Service\ZeusService'
            ),
            'factories' => array(               
                'EbsOptions' => function($sm) {
                    $config = $sm->get('Config');   
                    return new \TvGrabber\Options\EbsOptions($config['ebs_options']);
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
                'FileTable' => function($sm) { 
                    $fileTable = new Model\Table\FileTable(
                        'file', $sm->get('CatchuptvDbAdapter')
                    );
                    $fileTable->setServiceLocator($sm);
                    return $fileTable;
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
                'EbsOpt' => 'EbsOptions',
                'EpgModel' => 'EpgTable', 
                'FileModel' => 'FileTable', 
                'LiveStreamsModel' => 'LiveStreamsTable', 
            )
        );
    }   
}
