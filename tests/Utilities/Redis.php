<?php
    namespace Cerceau\Test\Utilities;

	class Redis extends \Cerceau\NoSQL\Redis {

        protected static $isOn = true;

        public function get( $spotId = 1 ){
            if(!self::$isOn )
                throw new \UnexpectedValueException();

            return parent::get( $spotId );
        }

        /**
         * @param bool $on
         */
        public static function state( $on = true ){
            self::$isOn = $on;
        }
	}