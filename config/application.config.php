<?php
return array(
    'modules' => array(
        'TvGrabber',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir' => 'data/cache',
        'config_static_paths' => array(
            __DIR__ . '/autoload/development.config.php',
        ),
        'module_paths' => array(
            'TvGrabber' => __DIR__ . '/../module/TvGrabber',
        ),
        'lazy_loading' => array(
            'TvGrabber' => array (
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
