<?php
    namespace Cerceau\Database\I;

	interface ISQLTemplator {
        /**
         * @abstract
         * @param string $tpl
         * @param array $args
         * @param null|int $spotId
         * @return string
         */
		public function parseSQL( $tpl, $args, $spotId = null );
	}
	