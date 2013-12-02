<?php
namespace Mm\ModuleManager\Listener;

use Zend\ServiceManager\AbstractPluginManager;
use Mm\ModuleManager\Listener\Exception\InvalidListenerException;

class ListenerManager extends AbstractPluginManager
{
    protected $invokableClasses = array(
        'datetime'  => 'Mm\ModuleManager\Listener\Server\DateTime',
        'hostname'  => 'Mm\ModuleManager\Listener\Server\DomainListener',
        'getopt' => 'Mm\ModuleManager\Listener\Environment\GetoptListener',
        'http_method' => 'Mm\ModuleManager\Listener\Server\HttpMethod',
    	'https' => 'Mm\ModuleManager\Listener\Server\HttpsListener',
        'port' => 'Mm\ModuleManager\Listener\Server\PortListener',
    	'remoteaddr' => 'Mm\ModuleManager\Listener\Server\RemoteAddrListener',
        'sapi' => 'Mm\ModuleManager\Listener\Environment\SapiListener',
        'url' => 'Mm\ModuleManager\Listener\Server\UrlListener',
        'logger' => 'Mm\ModuleManager\Listener\Server\Logger',
        'token' => 'Mm\ModuleManager\Listener\Server\TokenListener',
    );

    protected $aliases = array(
        'domain' => 'hostname',
        'uri' => 'url',
        'remote_addr' => 'remoteaddr',
        'ip' => 'remoteaddr',
    );

    public function get($name, $usePeeringServiceManagers = true)
    {
        $plugin = parent::get($name, $usePeeringServiceManagers);
        return $plugin;
    }
    
    public function validatePlugin($plugin)
    {
    	if ($plugin instanceof AuthorizeHandlerInterface) {
            return;
        }
    	throw new InvalidListenerException(
            'Auth listeners must implement AuthorizeHandlerInterface'
        );
    }
}
