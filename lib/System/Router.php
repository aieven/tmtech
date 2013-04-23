<?php
    namespace Cerceau\System;

    class Router implements \Cerceau\I\IRouter {
        const
            CONTROLLERS_DIR = 'Controller',
            CONTROLLERS_NAMESPACE = '\\Cerceau\\Controller',

            PREG_DELIMITER = '#';

        private
            $ajax = false,
            $url = '',
            $method = 'get',
            $routes = array();

        /**
         * @var Router
         */
        protected static $Instance;

        protected function __construct(){
            if(
                isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) &&
                ( 'XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH'] )
            )
                $this->ajax = true;

            $DynamicConfigFile = \Cerceau\Utilities\DynamicConfigFile::instance();

            $this->method = strtolower( $_SERVER['REQUEST_METHOD'] );
            $this->routes = $DynamicConfigFile->read( $this->method, \Cerceau\Config\Constants::ROUTES_CONFIG_DIR );
        }

        /**
         * @static
         * @return Router
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @static
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
            $this->url = $_GET['q'];
            unset( $_GET['q'] );

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

            throw new \InvalidArgumentException( 'Wrong query. Route "'. $this->url .'" not exists' );
        }

        /**
         * @return string
         */
        public function getUrl(){
            return $this->url;
        }

        /**
         *
         */
        public function cacheRoutes(){
            $routes = $this->loadRoutes();
            foreach( $routes as $method => &$methodRoutes ){
                ksort( $methodRoutes, SORT_DESC );

                $DynamicConfigFile = \Cerceau\Utilities\DynamicConfigFile::instance();
                $DynamicConfigFile->write( $method, $methodRoutes, \Cerceau\Config\Constants::ROUTES_CONFIG_DIR );
            }
        }

        /**
         * @param string $namespace
         * @return array
         * @throws \UnexpectedValueException
         */
        private function loadRoutes( $namespace = '' ){
            $DirectoryIterator = new \DirectoryIterator(
                LIB_DIR . self::CONTROLLERS_DIR . str_replace( '\\', '/', $namespace )
            );
            $allRoutes = array(
                'get' => array(),
                'post' => array(),
                'head' => array(),
                'put' => array(),
                'delete' => array(),
                'options' => array(),
            );
            foreach( $DirectoryIterator as $Current ){
                /**
                 * @var \DirectoryIterator $Current
                 */
                if( $Current->isDot())
                    continue;
                $subName = $Current->getBasename( '.php' );
                $currentNamespace = $namespace .'\\'. $subName;
                if( $Current->isDir()){
                    $appendRoutes = $this->loadRoutes( $currentNamespace );
                    foreach( $allRoutes as $method => &$methodRoutes ){
                        $methodRoutes += $appendRoutes[$method];
                    }
                }
                elseif( $Current->isFile()){
                    $className = self::CONTROLLERS_NAMESPACE . $currentNamespace;
                    foreach( $allRoutes as $method => &$methodRoutes ){
                        $preRoutes = $className::routes( $method );
                        $routes = array();
                        foreach( $preRoutes as $routeName => $route ){
                            array_shift( $route );
                            array_unshift( $route, $routeName );
                            array_unshift( $route, $className );
                            $routes[$route[2]] = $route;
                        }

                        if( array_intersect_key( $methodRoutes, $routes ) )
                            throw new \UnexpectedValueException( 'Routes conflicts' );

                        $methodRoutes += $routes;
                    }
                }
            }
            return $allRoutes;
        }
    }