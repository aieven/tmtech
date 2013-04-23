<?php
    namespace Cerceau\Utilities\I;

    interface IDomainConfig {

        /**
         * @abstract
         * @return string
         */
        public function main();

        /**
         * @abstract
         * @param $name
         * @return string
         */
        public function sub( $name );

        /**
         * @abstract
         * @param $name
         * @param $id
         * @return string
         */
        public function spot( $name, $id );

    }