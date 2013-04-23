<?php
    namespace Cerceau\Database\I;

	interface ISpotConnection {

        /**
         * @abstract
         * @param string $name
         * @param int $spotId
         * @return IDatabase
         */
        public function get( $name, $spotId );
	}