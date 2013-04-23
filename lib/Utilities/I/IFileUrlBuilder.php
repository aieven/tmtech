<?php
    namespace Cerceau\Utilities\I;

    interface IFileUrlBuilder {
        /**
         * @abstract
         * @param array $data
         * @return string
         */
        public function url( \ArrayAccess $data );

        /**
         * @abstract
         * @param array $data
         * @return string
         */
        public function path( \ArrayAccess $data );

        /**
         * @abstract
         * @param array $data
         * @return string
         */
        public function relativePath( \ArrayAccess $data );
    }
	