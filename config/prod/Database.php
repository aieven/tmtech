<?php
    namespace Cerceau\Config;

    class Database extends \Cerceau\Database\Config {
        protected static $databases = array(
            'main' => array(
                'driver' => 'PostgreSQL',
                'host' => '93.170.106.95',
                'port' => '5432',
                'user' => 'classygram',
                'password'  => 'gAs9edr7TAW2E',
                'dbname' => 'classygram',
                'charset' => 'UTF8',
            ),
            'counters' => array(
                'driver' => 'PostgreSQL',
                'host' => '93.170.106.95',
                'port' => '5432',
                'user' => 'classygram',
                'password'  => 'gAs9edr7TAW2E',
                'dbname' => 'classygram_counters',
                'charset' => 'UTF8',
            ),
        );
    }
