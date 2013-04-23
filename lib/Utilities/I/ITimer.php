<?php
    namespace Cerceau\Utilities\I;

    interface ITimer {

        /**
         * @return int
         */
        public function now();

        /**
         * @param bool $increment
         * @return float
         */
        public function micro( $increment = false );
    }