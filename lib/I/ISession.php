<?php
    namespace Cerceau\I;

    interface ISession extends \ArrayAccess {

        /**
         * @abstract
         * @return bool
         */
        public function save();

        /**
         * @abstract
         * @return string
         */
        public function getName();

        /**
         * @abstract
         * @param $name
         * @param string|null $value
         * @param int|null $expire
         */
        public function setCookie( $name, $value = null, $expire = null );
    }
	