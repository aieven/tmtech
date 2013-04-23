<?php
	namespace Cerceau\I;

	interface IController {
        /**
         * @static
         * @abstract
         * @param string $method
         */
        public static function routes( $method );

        /**
         * @abstract
         * @return string
         */
        public function run();
	}
	