<?php
return array(
    'modules' => array(
        'XMLTV',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir' => 'data/cache',
        'config_static_paths' => array(
            __DIR__ . '/autoload/development.config.php',
        ),
        'module_paths' => array(
            'XMLTV' => __DIR__ . '/../module/XMLTV',
        ),
        'lazy_loading' => array(
            'XMLTV' => array (
                'sapi' => 'cli',
            ),
        ),
    ),
    'service_manager' => array(
        'factories'    => array(
            'ModuleManager' => 'Mm\Mvc\Service\ModuleManagerFactory',
        ),
    ),
);
