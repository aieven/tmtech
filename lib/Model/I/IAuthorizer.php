<?php
	namespace Cerceau\Model\I;

	interface IAuthorizer extends ISpoted {

        /**
         * @abstract
         * @param string $key
         * @param array $data
         * @return bool
         */
        public function set( $key, array $data );

        /**
         * @abstract
         * @param string $key
         * @return array|bool
         */
        public function get( $key );

        /**
         * @abstract
         * @param string $key
         * @return bool
         */
        public function remove( $key );
	}
	