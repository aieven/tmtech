<?php
    namespace Cerceau\Database\I;

	interface IConnection {

        /**
         * @param string $name
         * @return IDatabase
         */
        public function get( $name );
	}