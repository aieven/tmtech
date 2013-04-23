<?php
	namespace Cerceau\Model\I;

	interface IUniqueId {

        /**
         * @abstract
         * @param int $id
         * @return string|bool
         */
        public function allocate( $id = 1 );

        /**
         * @abstract
         * @param string $key
         * @return bool
         */
        public function exists( $key );

        /**
         * @abstract
         * @param string $key
         * @return int
         */
        public function get( $key );

        /**
         * @abstract
         * @param string $key
         * @param int $id
         * @return bool
         */
        public function set( $key, $id );
	}
	