<?php
    namespace Cerceau\Config;

    class Database extends \Cerceau\Database\Config {
        protected static $databases = array(
            'main' => array(
                'driver' => 'PostgreSQL',
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'classygram',
                'password'  => 'J(QGmq3t-lof#4',
                'dbname' => 'classygram_test',
                'charset' => 'UTF8',
            ),
            'counters' => array(
                'driver' => 'PostgreSQL',
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'classygram',
                'password'  => 'J(QGmq3t-lof#4',
                'dbname' => 'classygram_counters_test',
                'charset' => 'UTF8',
            ),
        );
    }
