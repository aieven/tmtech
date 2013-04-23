<?php
    namespace Cerceau\IO;

    class TestRequest implements \Cerceau\I\IRequest {

        /**
         * @var \Cerceau\IO\TestRequest
         */
        private static $Instance;

        private $cookie, $get, $post;

        /**
         * @return \ArrayAccess
         */
        public function cookies(){
            return $this->cookie;
        }

        /**
         * @return \ArrayAccess
         */
        public function get(){
            return $this->get;
        }

        /**
         * @return \ArrayAccess
         */
        public function post(){
            return $this->post;
        }

        public function __construct( $cookie = array(), $get = array(), $post = array()){
            $this->cookie = $cookie;
            $this->get    = $get;
            $this->post   = $post;
        }

        public static function instance(){
            if(!self::$Instance )
                self::$Instance = new static();
            return self::$Instance;
        }

        public static function reset( $cookie = array(), $get = array(), $post = array()){
            self::$Instance = new static( $cookie, $get, $post );
        }
    }
	