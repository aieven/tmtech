<?php
	namespace Cerceau\Mail;

    class Config {
        protected static $settings = array();

        private static $configs = array();

        private $config = array();

        private function __construct( $config ){
            $this->config = $config;
        }

        public function __get( $name ){
            if( isset( $this->config[$name] ))
                return $this->config[$name];

            return null;
        }

        /**
         * @static
         * @param $name
         * @return Config
         * @throws \UnexpectedValueException
         */
        final public static function get( $name ){
            if(!array_key_exists( $name, static::$settings ))
                throw new \UnexpectedValueException( 'Mail config "'. $name .'" is not defined' );

            if(!array_key_exists( $name, self::$configs ))
                self::$configs[$name] = new static( static::$settings[$name] );

            return self::$configs[$name];
        }
    }
	