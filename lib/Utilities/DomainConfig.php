<?php
    namespace Cerceau\Utilities;

    class DomainConfig implements I\IDomainConfig {
        /**
         * @var SpecialConfig
         */
        protected static $Instance;
        protected static $domain;
        protected static $subs = array();

        /**
         * @static
         * @return SpecialConfig
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        public function main( $protocol = 'http' ){
            return $protocol ? $protocol .'://'. static::$domain .'/' : static::$domain;
        }

        public function sub( $name, $http = true ){
            if(!isset( static::$subs[$name] ))
                return null;

            return $http ? 'http://'. static::$subs[$name] .'.'. static::$domain .'/' : static::$subs[$name] .'.'. static::$domain;
        }

        public function spot( $name, $id, $http = true ){
            if(!isset( static::$subs[$name] ))
                return null;

            $domain = static::$subs[$name] . intval( $id ) .'.'. static::$domain;

            return $http ? 'http://'. $domain : $domain;
        }
    }
