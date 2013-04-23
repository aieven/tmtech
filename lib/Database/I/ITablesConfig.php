<?php
    namespace Cerceau\Database\I;

	interface ITablesConfig {
        /**
         * @abstract
         * @param string $alias
         * @return string
         */
		public function getTable( $alias );
	}
	