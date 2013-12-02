<?php

date_default_timezone_set('Europe/London');

require_once __DIR__ . '/../init_autoloader.php';

use Zend\Loader\StandardAutoloader;

$autoloader = new StandardAutoloader(array('autoregister_zf' => true));
$autoloader->registerNamespace('ModuleTest', __DIR__ . '/module/');
//$autoloader->registerNamespace('XMLTV', __DIR__ . '/../module/XMLTV/src/XMLTV');
$autoloader->registerNamespace('Zend', __DIR__ . '/../vendor/ZendX');
//$autoloader->registerNamespace('Zend', __DIR__ . '/../vendor/ZF2/library/');
$autoloader->register();
