<?php
    namespace Cerceau\System;

    class RouterTest implements \Cerceau\I\IRouter {
        const
            CONTROLLERS_DIR = 'Controller',
            CONTROLLERS_NAMESPACE = '\\Cerceau\\Controller',

            PREG_DELIMITER = '#';

        private
            $method = 'get',
            $url = '',
            $ajax = false,
            $routes = array();

        /**
         * @var Router
         */
        protected static $Instance;

        /**
         * @static
         * @return Router
         */
        public static function instance(){
            if(!static::$Instance ){
                throw \Exception();
            }
            return static::$Instance;
        }

        public function __construct( $method, $url, $ajax = false ){
            $this->method = $method;
            $this->url = $url;
            $this->ajax = $ajax;
            $this->routes = \Cerceau\Utilities\DynamicConfigFile::instance()->read( $this->method, \Cerceau\Config\Constants::ROUTES_CONFIG_DIR );
            self::$Instance = $this;
        }

        /**
         * @param string $route
         * @param string $url
         * @param null|array $matches
         * @return bool
         */
        protected function preg( $route, $url, &$matches = null ){
            $route = strtr( $route, array(
                '<str>'  => '([\w'. \Cerceau\ALPHABET .']+)',
                '<estr>' => '([\w\s\-'. \Cerceau\ALPHABET .']*)',
                '<int>'  => '(\d+)',
            ));
            $route = preg_replace( '#<([^>]*)>#', '($1)', $route );
            $preg = self::PREG_DELIMITER .'^'. $route .'$'. self::PREG_DELIMITER;
            if(!preg_match( $preg, $url, $matches ))
                return false;
            if( $matches )
                array_shift( $matches );
            return true;
        }

        /**
         * @return \Cerceau\I\IController
         * @throws \InvalidArgumentException
         */
        public function getController(){
            foreach( $this->routes as $preg => $routeInfo ){
                $className = array_shift( $routeInfo );
                $page = array_shift( $routeInfo );
                $params = array_key_exists( 'params', $routeInfo ) ? $routeInfo['params'] : array();

                if( $this->preg( $preg, $this->url, $matches )){
                    if( count( $matches ) !== count( $params ))
                        throw new \InvalidArgumentException( 'Wrong query. Invalid number of parameters.' );

                    $query = $matches ? array_combine( $params, $matches ) : array();

                    return new $className( $this->method, $page, $query, $this->ajax );
                }
            }

            throw new \InvalidArgumentException( 'Wrong query. Route not exists' );
        }

        /**
         * @return string
         */
        public function getUrl(){
            return $this->url;
        }
    }