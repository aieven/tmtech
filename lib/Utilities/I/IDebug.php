<?php
    namespace Cerceau\Utilities\I;

    interface IDebug {

        /**
         * @param mixed $var
         * @param bool $exit
         * @param string $class
         */
        public function dump( $var, $exit = false, $class = 'debug' );

        /**
         * @param mixed $var
         * @param bool $exit
         */
        public function json( $var, $exit = true );

        /**
         * @param mixed $var
         * @return string
         */
        public function printVar( $var );
    }