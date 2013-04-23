<?php
    namespace Cerceau\Utilities;

    class SpecialConfig implements I\ISpecialConfig {
        /**
         * @var SpecialConfig
         */
        protected static $Instance;

        protected static $defaults = array();

        protected $values;

        protected function __construct(){
            $this->values = static::$defaults;
        }

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

        public function get( $offset ){
            if(!array_key_exists( $offset, $this->values ))
                return null;

            return $this->values[$offset];
        }

        public function set( $offset, $value ){
            $this->values[$offset] = $value;
        }


        public function offsetExists( $offset ){
            return isset( $this->values[$offset] );
        }

        public function offsetGet( $offset ){
            return $this->get( $offset );
        }

        public function offsetSet( $offset, $value ){
            $this->set( $offset, $value );
        }

        public function offsetUnset( $offset ){
            if( isset( $this->values[$offset] ))
                unset( $this->values[$offset] );
        }
    }