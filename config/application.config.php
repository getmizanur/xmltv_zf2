<?php
return array(
    'modules' => array(
        'TvGrabber',
        'Epg',
        //'PowerLinks'
    ),
    'module_listener_options' => array( 
        'config_cache_enabled' => false,
        'cache_dir' => 'data/cache',
        'config_static_paths' => array(
            __DIR__ . '/autoload/development.config.php',
        ),
        'module_paths' => array(
            'TvGrabber' => __DIR__ . '/../module/TvGrabber',
            'Epg' => __DIR__ . '/../module/Epg',
            //'PowerLinks' => __DIR__ . '/../module/PowerLinks',
        ),
        'lazy_loading' => array(
            'TvGrabber' => array (
                'sapi' => 'cli',
            ),
            'Epg' => array(
                'hostname' => 'epg.local.xmltv',
                'port' => 80,
            ),
            //'PowerLinks' => array (
                //'sapi' => 'cli',
            //),

        ),
    ),
    'service_manager' => array(
        'factories'    => array(
            'ModuleManager' => 'Mm\Mvc\Service\ModuleManagerFactory',
        ),
    ),
);
