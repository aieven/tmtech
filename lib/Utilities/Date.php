<?php
    namespace Cerceau\Utilities;

    class Date implements I\IDate {

        /**
         * @var Date
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Date
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * Int: timestamp
         * String: dd/mm/YYYY H:i:s or YYYY/mm/dd H:i:s
         * Array: y, m, d, h, i, s
         *
         * @param mixed $mixed
         * @return int
         */
        public function timestamp( $mixed = null ){
            if( is_null( $mixed ))
                return time();

            if( is_numeric( $mixed ))
                return intval( $mixed );

            if( is_array( $mixed )){
                $y = array_shift( $mixed ) or $y = intval(date('Y'));
                $m = array_shift( $mixed ) or $m = intval(date('n'));
                $d = array_shift( $mixed ) or $d = intval(date('j'));
                $h = array_shift( $mixed ) or $h = 0;
                $i = array_shift( $mixed ) or $i = 0;
                $s = array_shift( $mixed ) or $s = 0;

                return mktime( $h, $i, $s, $m, $d, $y );
            }

            if( is_string( $mixed ))
                return strtotime( $mixed );

            return null;
        }

        public function datestamp( $mixed = null ){
            $time = $this->timestamp( $mixed );
            return mktime( 0,0,0, date( 'n', $time ), date( 'j', $time ), date( 'Y', $time ));
        }

        public function appendDaysTo( $datestamp, $days = 1 ){
            return mktime( 0,0,0, date( 'n', $datestamp ), date( 'j', $datestamp ) + $days, date( 'Y', $datestamp ));
        }
    }