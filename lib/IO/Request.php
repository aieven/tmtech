<?php
    namespace Cerceau\IO;

    class Request implements \Cerceau\I\IRequest {

        /**
         * @return \ArrayAccess
         */
        public function cookies(){
            return $_COOKIE;
        }

        /**
         * @return \ArrayAccess
         */
        public function get(){
            return $_GET;
        }

        /**
         * @return \ArrayAccess
         */
        public function post(){
            return $_POST;
        }

        /**
         * @var \Cerceau\I\IRequest
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return \Cerceau\I\IRequest
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }
    }
	