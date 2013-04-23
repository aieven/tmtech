<?php
    namespace Cerceau\Utilities;

    class UrlBuilder implements I\IUrlBuilder {
        /**
         * @var UrlBuilder
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return UrlBuilder
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         */
        public function page( $controllerName, $pageName, array $arguments = array()){
            return $this->route( 'get', $controllerName, $pageName, $arguments );
        }

        /**
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         */
        public function form( $controllerName, $pageName, array $arguments = array()){
            return $this->route( 'post', $controllerName, $pageName, $arguments );
        }

        /**
         * @param string $method
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         * @throws \UnexpectedValueException
         */
        public function route( $method, $controllerName, $pageName, array $arguments = array()){
            $controllerClass = '\\Cerceau\\Controller\\'. ucfirst( $controllerName );
            $route = $controllerClass::route( $method, $pageName );
            if(!$route )
                throw new \UnexpectedValueException( 'No such page "'. $pageName .'" in '. $controllerClass .' for method '. $method );
            array_shift( $route );
            $path = array_shift( $route );
            $params = isset( $route['params'] ) ? $route['params'] : array();
            foreach( $params as $param ){
                $arg = isset( $arguments[$param] ) ? $arguments[$param] : '';
                $path = preg_replace( '#<[^>]*>#', $arg, $path, 1 );
            }
            return $path;
        }

        /**
         * @param string $localPath
         * @param string $theme
         * @return string
         */
        public function image( $localPath, $theme = null ){
            return \Cerceau\System\Registry::instance()->DomainConfig()->main() .'img/'. ( $theme ? $theme .'/' : '' ). $localPath;
        }

        /**
         * @return string
         */
        public function sitename(){
            return $_SERVER['HTTP_HOST'];
        }
    }