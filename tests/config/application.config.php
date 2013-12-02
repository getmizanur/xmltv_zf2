<?php
return array(
    'modules' => array(
        'XMLTV',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir' => 'data/cache',
        'config_static_paths' => array(
            __DIR__ . '/../../config/autoload/development.config.php',
        ),
        'module_paths' => array(
            'XMLTV' => __DIR__ . '/../../module/XMLTV',
        ),
    ),
);
