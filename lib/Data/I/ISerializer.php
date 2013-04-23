<?php
	namespace Cerceau\Data\I;

	interface ISerializer {
        /**
         * @abstract
         * return string
         */
		public function serialize( $data );

        /**
         * @abstract
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string );

        /**
         * @abstract
         * @param array $options
         */
        public function options( array $options );
	}
	