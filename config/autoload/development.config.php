<?php
return array(
    'db_adapter_manager' => array(
        'xmltv' => array (
            'catchuptv' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=catchuptv;host=localhost',
                'username' => 'root',
                'password' => 'eskimo',
            ),
            'simplestreamClients' => array(
                'driver' => 'Pdo',
                'dsn' => 'mysql:dbname=simplestream_clients;host=localhost',
                'username' => 'root',
                'password' => 'eskimo',
            ),
        ),
    ),
);
