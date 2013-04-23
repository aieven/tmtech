<?php
    namespace Cerceau\Config;

    class Database extends \Cerceau\Database\Config {
        protected static $databases = array(
            'main' => array(
                'driver' => 'PostgreSQL',
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'tmtech',
                'password'  => 'SFsd42sacd',
                'dbname' => 'tmtech',
                'charset' => 'UTF8',
            ),
        );
    }
