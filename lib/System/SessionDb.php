<?php
    namespace Cerceau\System;

    class SessionDb implements \Cerceau\I\ISession {

        /**
         * @var SessionDb
         */
        private static $Instance;

        /**
         * @var \Cerceau\I\IResponse
         */
        private $Response;

        /**
         * @var \Cerceau\Data\User\Session
         */
        private $Data;

        private
            $loaded = false,
            $cookies = array();

        protected function __construct( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response ){
            $this->Data = new \Cerceau\Data\User\Session();
            $this->Response = $Response;
            $authHash = md5(
                $_SERVER['HTTP_ACCEPT_ENCODING'] .','.
                $_SERVER['HTTP_ACCEPT_LANGUAGE'] .','.
                $_SERVER['HTTP_ACCEPT_CHARSET']
            );
            if($this->Data->load( $Request->cookies())){
                // valid session ip
                if( $this->Data['data']['auth_data']['ip'] === $_SERVER['REMOTE_ADDR'] )
                    $this->loaded = true;

                // invalid session
                if(
                    $this->Data['data']['auth_data']['ua'] !== $_SERVER['HTTP_USER_AGENT'] ||
                    $this->Data['data']['auth_data']['hash'] !== $authHash
                )
                    throw new \Cerceau\Exception\HackAttempt( 'Anauthorized access attempts from '. $_SERVER['REMOTE_ADDR'] );
            }

            // new session
            if(!$this->loaded ){
                $this->Data['data'] = array(
                    'auth_data' => array(
                        'ip'   => $_SERVER['REMOTE_ADDR'],
                        'ua'   => $_SERVER['HTTP_USER_AGENT'],
                        'hash' => $authHash,
                    ),
                );
            }
        }

        /**
         * @static
         * @param \Cerceau\I\IRequest $Request
         * @param \Cerceau\I\IResponse $Response
         * @param boolean $renew
         * @return SessionDb
         */
        public static function instance( \Cerceau\I\IRequest $Request, \Cerceau\I\IResponse $Response, $renew = false ){
            if(!self::$Instance || $renew )
                self::$Instance = new self( $Request, $Response );
            return self::$Instance;
        }

        final public function offsetExists( $offset ){
            return isset( $this->Data['data'][$offset] );
        }

        final public function offsetGet( $offset ){
            return $this->Data['data'][$offset];
        }

        final public function offsetSet( $offset, $value ){
            if( $this->Data['data'][$offset] !== $value ){
                $this->Data->setData( $offset, $value );
            }
        }

        final public function offsetUnset( $offset ){
            if( isset( $this->Data['data'][$offset] )){
                $this->Data->unsetData( $offset );
            }
        }

        public function setCookie( $name, $value = null, $expire = null ){
            $this->cookies[$name] = array(
                'value' => $value,
                'expire' => $expire
            );
        }

        public function save(){
            if( $this->loaded ){
                if(!$this->Data->update())
                    return false;
            }
            else{
                if(!$this->Data->create())
                    return false;
                $this->setCookie( 'ssid', $this->Data['ssid'] );
            }

            foreach( $this->cookies as $name => $cookie ){
                $this->Response->cookie( $name, $cookie['value'], $cookie['expire'] );
            }
            return true;
        }

        public function getName(){
            return $this->Data['ssid'];
        }
    }
