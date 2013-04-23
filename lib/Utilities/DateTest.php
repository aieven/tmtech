<?php
    namespace Cerceau\Utilities;

    class DateTest extends Date {

        private static $now;

        public function __construct(){
            self::$now = time();
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
                return self::$now;

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

        public static function setTime( $now ){
            self::$now = self::instance()->timestamp( $now );
        }
    }