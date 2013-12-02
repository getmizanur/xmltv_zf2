<?php

require_once __DIR__ . '/vendor/ZF2/library/Zend/Loader/AutoloaderFactory.php';
Zend\Loader\AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,
    ),
    'Zend\Loader\ClassMapAutoloader' => array(
        __DIR__ . '/config/autoload_classmap.php'
    ),
));
