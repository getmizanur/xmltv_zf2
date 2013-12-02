<?php
namespace Mm\Mvc\Service;

use Mm\ModuleManager\Listener\AuthListenerAggregate;
use Mm\ModuleManager\Listener\ListenerOptions;
use Mm\ModuleManager\ModuleManager;
use Mm\ModuleManager\ModuleEvent;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('ServiceListener')) {
            $serviceLocator->setFactory(
                'ServiceListener', 
                'Zend\Mvc\Service\ServiceListenerFactory'
            );
        }
        
        $configuration    = $serviceLocator->get('ApplicationConfig');
        $listenerOptions  = new ListenerOptions($configuration['module_listener_options']);
        $defaultListeners = new AuthListenerAggregate($listenerOptions);
        $serviceListener  = $serviceLocator->get('ServiceListener');

        $serviceListener->addServiceManager(
            $serviceLocator,
            'service_manager',
            'Zend\ModuleManager\Feature\ServiceProviderInterface',
            'getServiceConfig'
        );
        $serviceListener->addServiceManager(
            'ControllerLoader',
            'controllers',
            'Zend\ModuleManager\Feature\ControllerProviderInterface',
            'getControllerConfig'
        );
        $serviceListener->addServiceManager(
            'ControllerPluginManager',
            'controller_plugins',
            'Zend\ModuleManager\Feature\ControllerPluginProviderInterface',
            'getControllerPluginConfig'
        );
        $serviceListener->addServiceManager(
            'ViewHelperManager',
            'view_helpers',
            'Zend\ModuleManager\Feature\ViewHelperProviderInterface',
            'getViewHelperConfig'
        );

        $events = $serviceLocator->get('EventManager');
        $events->attach($defaultListeners);
        $events->attach($serviceListener);

        $moduleEvent = new ModuleEvent;
        $moduleEvent->setParam('ServiceManager', $serviceLocator);

        $moduleManager = new ModuleManager($configuration['modules'], $events);
        $moduleManager->setEvent($moduleEvent);

        return $moduleManager;
    }
}
