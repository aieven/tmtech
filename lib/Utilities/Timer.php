<?php
    namespace Cerceau\Utilities;

    class Timer implements I\ITimer {
        protected $microIncrement = 0;

        /**
         * @var Timer
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Timer
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @return int
         */
        public function now(){
            return time();
        }

        /**
         * @param bool $increment
         * @return float
         */
        public function micro( $increment = false ){
            if( $increment ){
                $this->microIncrement += 0.1;
                return microtime(true) + $this->microIncrement;
            }
            return microtime(true);
        }

    }