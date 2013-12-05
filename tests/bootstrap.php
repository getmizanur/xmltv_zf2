<?php

date_default_timezone_set('Europe/London');

require_once __DIR__ . '/../init_autoloader.php';

use Zend\Loader\StandardAutoloader;

$autoloader = new StandardAutoloader();
$autoloader->registerNamespace('ModuleTest', __DIR__ . '/module/');
$autoloader->registerNamespace('TvGrabber', __DIR__ . '/../module/TvGrabber/src/TvGrabber');
//$autoloader->registerNamespace('Mm', __DIR__ . '/../vendor/');
$autoloader->registerNamespace('Zend', __DIR__ . '/../vendor/ZendX/');
//$autoloader->registerNamespace('Zend', __DIR__ . '/../vendor/zendframework/zendframework/library/');
$autoloader->register();

