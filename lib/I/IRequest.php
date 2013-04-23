<?php
    namespace Cerceau\I;

    interface IRequest {

        /**
         * @abstract
         * @return array
         */
        public function cookies();

        /**
         * @abstract
         * @return array
         */
        public function get();

        /**
         * @abstract
         * @return array
         */
        public function post();
    }
	