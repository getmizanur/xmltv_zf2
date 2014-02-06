<?php
ini_set('date.timezone',"Europe/London");

define('ZF2_TIME_START', microtime(true));

defined('APPLICATION_ENV')     
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? 
        getenv('APPLICATION_ENV') : 'production'));

//define('DB_PERSISTENCY', true);
//define('DB_SERVER', 'localhost');
//define('DB_SERVER', 'localhost');
//define('DB_USERNAME', 'root');
//define('DB_PASSWORD', 'eskimo');
//define('DB_PASSWORD', 'toor');
//define('DB_DATABASE', 'catchuptv');
//define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);

define('MODULE_PATH', dirname(__DIR__) . '/module');

chdir(dirname(__DIR__));

include 'init_autoloader.php';

setlocale(LC_CTYPE, 'nl_BE.utf8');

error_reporting(E_ERROR);

Zend\Mvc\Application::init(include 'config/application.config.php')->run();
