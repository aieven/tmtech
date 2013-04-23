<?php
    namespace Cerceau\Database\TablesConfig;

	abstract class Base implements \Cerceau\Database\I\ITablesConfig {
        protected static $tables;

        protected static $configs = array();

        private function __construct(){}

        /**
         * @param string $alias
         * @return string
         * @throws \UnexpectedValueException
         */
        public function getTable( $alias ){
            if(!array_key_exists( $alias, static::$tables ))
                throw new \UnexpectedValueException( 'Table for alias `'. $alias .'` is not defined' );
            return static::$tables[$alias];
        }

        /**
         * @static
         * @param $name
         * @return Base
         * @throws \UnexpectedValueException
         */
        public static function instance( $name ){
            $className = __NAMESPACE__ .'\\'. ucfirst( $name );

            if(!class_exists( $className, true ))
                throw new \UnexpectedValueException( 'Tables Config for database "'. $name .'" does not exist' );

            if(!isset( static::$configs[$name] ) )
                static::$configs[$name] = new $className();

            return static::$configs[$name];
        }
	}