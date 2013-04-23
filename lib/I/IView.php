<?php
	namespace Cerceau\I;

	interface IView {
        /**
         * Set data param
         *
         * @abstract
         * @param string|array $name
         * @param mixed $value
         */
        public function set( $name, $value = null );

        /**
         * @abstract
         * @return string
         */
        public function render();
	}
	