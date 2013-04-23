<?php
    namespace Cerceau\System;

    class SessionTest implements \Cerceau\I\ISession {

        /**
         * @var SessionTest
         */
        private static $Instance;

        /**
         * @var \Cerceau\I\IResponse
         */
        private $Response;

        private
            $cookies = array(),
            $data = array(),
            $name;

        protected function __construct( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response ){
            $this->Response = $Response;
            $this->name = md5( mt_rand( 0, 1000000 ));
        }

        /**
         * @static
         * @param \Cerceau\I\IRequest $Request
         * @param \Cerceau\I\IResponse $Response
         * @return SessionTest
         */
        public static function instance( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response ){
            if(!self::$Instance )
                self::$Instance = new self( $Request, $Response );
            return self::$Instance;
        }

        public static function reset(){
            self::$Instance = null;
        }

        final public function offsetExists( $offset ){
            return isset( $this->data[$offset] );
        }

        final public function offsetGet( $offset ){
            if( isset( $this->data[$offset] ))
                return $this->data[$offset];
            else
                return null;
        }

        final public function offsetSet( $offset, $value ){
            $this->data[$offset] = $value;
        }

        final public function offsetUnset( $offset ){
            if( isset( $this->data[$offset] ))
                unset( $this->data[$offset] );
        }

        public function setCookie( $name, $value = null, $expire = null ){
            $this->cookies[$name] = array(
                'value' => $value,
                'expire' => $expire
            );
        }

        public function save(){
            foreach( $this->cookies as $name => $cookie ){
                $this->Response->cookie( $name, $cookie['value'], $cookie['expire'] );
            }
            return true;
        }

        public function getName(){
            return $this->name;
        }
    }
