<?php
    namespace Cerceau\Utilities\I;

    interface IDate {

        /**
         * @param mixed $mixed
         * @return int
         */
        public function timestamp( $mixed = null );

        /**
         * @param mixed $mixed
         * @return int
         */
        public function datestamp( $mixed = null );

        /**
         * @param int $datestamp
         * @param int $days
         * @return int
         */
        public function appendDaysTo( $datestamp, $days = 1 );
    }