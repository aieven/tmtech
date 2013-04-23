<?php
	namespace Cerceau\Data\I;

	interface IEnum {
        /**
         * @abstract
         * @return array
         */
        public function options();

        /**
         * @abstract
         * @param int $value
         * @return string
         */
        public function name( $value );

        /**
         * @abstract
         * @param string $name
         * @return int
         */
        public function searchValue( $name );
    }
	