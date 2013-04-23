<?php
    namespace Cerceau\System;

    abstract class Controller implements \Cerceau\I\IController {
        protected static
            $routes = array(),
            $css = array(),
            $js = array();

        /**
         * @var \Cerceau\I\ISession $Session
         */
        protected $Session;

        protected
            $ajax,
            $page,
            $query,
            $viewType = 'Native',
            $unauthorizedPath = '';

        /**
         * @var \Cerceau\I\IHtmlView $View
         */
        protected $View;

        /**
         * @param $method
         * @param $page
         * @param array $query
         * @param bool $ajax
         * @throws \LogicException
         * @throws \UnexpectedValueException
         */
        final public function __construct( $method, $page, array $query, $ajax = false ){
            if( empty( static::$routes[$method][$page] ))
                throw new \UnexpectedValueException( 'Wrong query. Page '. $page .' for method '. $method .' not exists' );

            $routeInfo = static::$routes[$method][$page];
            $realPage = array_shift( $routeInfo );

            $this->page = 'page'. ucfirst( $realPage );
            if(!method_exists( $this, $this->page ))
                throw new \UnexpectedValueException( 'Page '. $realPage .' for controller '. get_class( $this ) .' is not implemented' );

            if( array_key_exists( 'view', $routeInfo ))
                $this->viewType = $routeInfo['view'];

            $this->ajax = $ajax;
            $this->query = $query;

            $className = '\\Cerceau\\View\\'. $this->viewType;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'View '. $this->viewType .' is not implemented' );

            $this->View = new $className( $ajax );
        }

        protected function initialize(){}
        protected function deinitialize(){}

        public static function routes( $method ){
            if(!array_key_exists( $method, static::$routes ))
                return array();

            return static::$routes[$method];
        }

        public static function route( $method, $pageName ){
            if(!isset( static::$routes[$method], static::$routes[$method][$pageName] ))
                return false;
            return static::$routes[$method][$pageName];
        }

        public function isAjax(){
            return $this->ajax;
        }

        /**
         * Get query string param
         *
         * @param string|null $name
         * @return mixed
         */
        final protected function queryParam( $name = null ){
            if(!$name )
                return $this->query;

            return array_key_exists( $name, $this->query ) ? $this->query[$name] : null;
        }

        public function run(){
            try {
                $this->Session = Registry::instance()->Session();
                $this->initialize();

                $viewInitialize = 'initialize'. $this->viewType;
                if( method_exists( $this, $viewInitialize ))
                    $this->$viewInitialize();

                $method = $this->page;
                if(!$this->$method())
                    throw new \Cerceau\Exception\Page();

                $this->deinitialize();
                $this->Session->save();

                $viewDeinitialize = 'deinitialize'. $this->viewType;
                if( method_exists( $this, $viewDeinitialize ))
                    $this->$viewDeinitialize();
            }
            catch( \Cerceau\Exception\RowValidation $E ){
                $this->View->set( 'fields_errors', json_decode( $E->getMessage()));
                if( $this->View instanceof \Cerceau\I\IHtmlView )
                    $this->View->template( 'message' );
            }
            catch( \Cerceau\Exception\Client $E ){
                $this->View->set( 'error', $E->getMessage());
                if( $this->View instanceof \Cerceau\I\IHtmlView )
                    $this->View->template( 'message' );
            }
            catch( \Cerceau\Exception\Page $E ){
                $header = $E->getMessage();
                $code = $E->getCode();
                if(!$code || ( $this->unauthorizedPath && $code === 401 )){
                    Registry::instance()->Response()->header( 'Location: /'. $this->unauthorizedPath );
                    return '';
                }
                Registry::instance()->Logger()->logException( $E );
                Registry::instance()->Response()->header( 'HTTP/1.0 '. $code .' '. $header );
                return '';
            }
            catch( \Cerceau\Exception\HackAttempt $E ){
                Registry::instance()->Logger()->log( 'hack-attempts', '['. $_SERVER['REMOTE_ADDR'] .'] '. $E->getMessage() );
                Registry::instance()->Response()->header( 'HTTP/1.0 404 Page not found' );
                return '';
            }
            catch( \Cerceau\Exception\Authorize $E ){
                // unauthorized
                Registry::instance()->Response()->header( 'HTTP/1.0 404 Page not found' );
                return '';
            }
            catch( \Exception $E ){
                throw $E;
            }

            return $this->View->render();
        }
    }