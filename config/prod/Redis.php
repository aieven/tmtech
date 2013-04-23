<?php
    namespace Cerceau\Config;

    class Redis extends \Cerceau\NoSQL\Redis {
        protected static $databases = array(
            'default' => array(
                'servers' => array(
                    'host' => 'localhost',
                    'port'     => 6382,
                ),
            ),
        );
    }
