<?php
    namespace Cerceau\System;

    class Session implements \Cerceau\I\ISession {

        /**
         * @var Session
         */
        private static $Instance;

        /**
         * @var \Cerceau\I\IResponse
         */
        private $Response;

        private
            $loaded = false,
            $cookies = array();

        protected function __construct( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response ){
            session_name( 'ssid' );
            session_start();

            if(!empty( $_SESSION ))
                $this->loaded = true;
        }

        /**
         * @static
         * @param \Cerceau\I\IRequest $Request
         * @param \Cerceau\I\IResponse $Response
         * @return Session
         */
        public static function instance( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response ){
            if(!self::$Instance )
                self::$Instance = new self( $Request, $Response );
            return self::$Instance;
        }

        final public function offsetExists( $offset ){
            return isset( $_SESSION[$offset] );
        }

        final public function offsetGet( $offset ){
            return isset( $_SESSION[$offset] ) ? $_SESSION[$offset] : null;
        }

        final public function offsetSet( $offset, $value ){
            $_SESSION[$offset] = $value;
        }

        final public function offsetUnset( $offset ){
            if( isset( $_SESSION[$offset] ))
                unset( $_SESSION[$offset] );
        }

        public function setCookie( $name, $value = null, $expire = null ){
            $this->cookies[$name] = array(
                'value' => $value,
                'expire' => $expire
            );
        }

        public function save(){
            foreach( $this->cookies as $name => $cookie ){
                Registry::instance()->Response()->cookie( $name, $cookie['value'], $cookie['expire'] );
            }
            return true;
        }

        public function getName(){
            return session_id();
        }
    }
