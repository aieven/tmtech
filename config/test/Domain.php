<?php
    namespace Cerceau\Config;

    class Domain extends \Cerceau\Utilities\DomainConfig {
        protected static $subs = array(
            'img' => 'img',
        );

        protected function __construct(){
            static::$domain = $_SERVER['HTTP_HOST'];
        }
    }
