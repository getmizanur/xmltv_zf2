<?php
return array(
    'modules' => array(
        'TvGrabber',
        'Epg',
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir' => 'data/cache',
        'config_static_paths' => array(
            __DIR__ . '/../../config/autoload/development.config.php',
        ),
        'module_paths' => array(
            'TvGrabber' => __DIR__ . '/../../module/TvGrabber',
            'Epg' => __DIR__ . '/../../module/Epg',
        ),
    ),
);
