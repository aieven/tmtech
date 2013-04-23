<?php
    namespace Cerceau\Test\Utilities;

    class Request {

        protected $token;
        protected $isForApi;

        public function __construct( $isForApi = true ){
            $this->isForApi = $isForApi;
        }

        public function setToken( $token = '7MCa1Oth2rfPq' ){
            $this->token = $token;
        }

        protected function getPage( $method, $url, $ajax = false ){
            $Router = new \Cerceau\System\RouterTest( $method, $url, $ajax );
            return $Router->getController()->run();
        }

        public function get( $controllerName, $pageName, $params = array(), $request = array() ){
            $Registry = \Cerceau\System\Registry::instance();
            $get = array();
            if( $this->isForApi ){
                $time = $Registry->Timer()->micro( true );
                $get = array(
                    't' => $time,
                    'c' => md5( $this->token . $time ),
                );
            }
            \Cerceau\IO\TestRequest::reset(
                array(), $get + $request
            );
            return json_decode(
                $this->getPage( 'get', $Registry->Url()->page( $controllerName, $pageName, $params ) ), true
            );
        }

        public function getHtml( $controllerName, $pageName, $params = array(), $request = array() ){
            $Registry = \Cerceau\System\Registry::instance();
            $get = array();
            if( $this->isForApi ){
                $time = $Registry->Timer()->micro( true );
                $get = array(
                    't' => $time,
                    'c' => md5( $this->token . $time ),
                );
            }
            \Cerceau\IO\TestRequest::reset(
                array(), $get + $request
            );
            return $this->getPage( 'get', $Registry->Url()->page( $controllerName, $pageName, $params ) );
        }

        public function post( $controllerName, $pageName, $params = array(), $request = array() ){
            $Registry = \Cerceau\System\Registry::instance();
            $get = array();
            if( $this->isForApi ){
                $time = $Registry->Timer()->micro( true );
                $get = array(
                    't' => $time,
                    'c' => md5( $this->token . $time ),
                );
            }
            \Cerceau\IO\TestRequest::reset(
                array(), $get, $request
            );
            return json_decode(
                $this->getPage( 'post', $Registry->Url()->form( $controllerName, $pageName, $params ) ), true
            );
        }

        public function delete( $controllerName, $pageName, $params = array() ){
            $Registry = \Cerceau\System\Registry::instance();
            $get = array();
            if( $this->isForApi ){
                $time = $Registry->Timer()->micro( true );
                $get = array(
                    't' => $time,
                    'c' => md5( $this->token . $time ),
                );
            }
            \Cerceau\IO\TestRequest::reset(
                array(), $get
            );
            return json_decode(
                $this->getPage( 'delete', $Registry->Url()->route( 'delete', $controllerName, $pageName, $params ) ),
                true
            );
        }
    }
