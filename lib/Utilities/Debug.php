<?php
    namespace Cerceau\Utilities;

    class Debug implements I\IDebug {
        /**
         * @var Debug
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Debug
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @param mixed $var
         * @param bool $exit
         * @param string $class
         */
        public function dump( $var, $exit = false, $class = 'debug' ){
            echo '<pre class="'. $class .'">'. $this->printVar( $var ) .'</pre>';
            if( $exit ) exit;
        }

        /**
         * @param mixed $var
         * @param bool $exit
         */
        public function json( $var, $exit = true ){
            echo '{"error":"'. str_replace( "\n", '<br />', htmlentities( $this->printVar( $var ))) .'"}';
            if( $exit ) exit;
        }

        /**
         * @param mixed $var
         * @return string
         */
        public function printVar( $var ){
            if(is_null( $var ))
                return 'NULL';
            if(is_bool( $var ))
                return 'bool'."\n". ( $var ? 'TRUE' : 'FALSE' );
            if(is_string( $var ))
                return 'string'."\n". $var;
            if(is_int( $var ))
                return 'int'."\n". $var;
            if(is_float( $var ))
                return 'float'."\n". $var;
            if(is_resource( $var ))
                return 'resource';
            if(is_array( $var ))
                return print_r( $var, true );
            if(is_object( $var ))
                return print_r( $var, true );
            return 'unknown type';
        }
    }