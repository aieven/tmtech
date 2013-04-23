<?php
    namespace Cerceau\Config;

    class Domain extends \Cerceau\Utilities\DomainConfig {
        protected static $subs = array(
            'resource' => 's',
        );

        protected function __construct(){
            static::$domain = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'classygram.com';
        }

        public function sub( $name ){
            return $this->main();
        }
    }
